-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2025 at 05:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rateflix`
--

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `banner_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`banner_id`, `name`, `description`, `image`) VALUES
(1, 'The Shawshank Redemption', 'Two imprisoned men bond over a number of years, finding solace and eventual redemption.', '/images/banner/shawshank.jpg'),
(2, 'Game of Thrones', 'Nine noble families wage war against each other to gain control over the mythical land of Westeros.', '/images/banner/got.jpg'),
(3, 'Breaking Bad', 'A high school chemistry teacher turned methamphetamine producer.', '/images/banner/bb.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Action'),
(2, 'Comedy'),
(4, 'Horror'),
(3, 'Romance');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `imdbID` varchar(20) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `rating` varchar(10) DEFAULT NULL,
  `release_date` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `imdbID`, `category_id`, `title`, `description`, `poster`, `rating`, `release_date`) VALUES
(1, 'tt4154756', 1, 'Avengers: Infinity War', 'As the Avengers and their allies have continued to protect the world from threats too large for any one hero to handle, a new danger has emerged from the cosmic shadows: Thanos. A despot of intergalactic infamy, his goal is to collect all six Infinity Stones, artifacts of unimaginable power, and use them to inflict his twisted will on all of reality. Everything the Avengers have fought for has led up to this moment, the fate of Earth and existence has never been more uncertain.', 'https://m.media-amazon.com/images/M/MV5BMjMxNjY2MDU1OV5BMl5BanBnXkFtZTgwNzY1MTUwNTM@._V1_SX300.jpg', '8.4', '2018'),
(2, 'tt4912910', 1, 'Mission: Impossible - Fallout', 'Two years after Ethan Hunt had successfully captured Solomon Lane, the remnants of the Syndicate have reformed into another organization called the Apostles. Under the leadership of a mysterious fundamentalist known only as John Lark, the organization is planning on acquiring three plutonium cores. Ethan and his team are sent to Berlin to intercept them, but the mission fails when Ethan saves Luther and the Apostles escape with the plutonium. With CIA agent August Walker joining the team, Ethan and his allies must now find the plutonium cores before it\'s too late.', 'https://m.media-amazon.com/images/M/MV5BZmUwZTg2YmMtMmZjOS00ZDYwLWI2ZDgtZDcyY2ZmMWMwZDdlXkEyXkFqcGc@._V1_SX300.jpg', '7.7', '2018'),
(3, 'tt4154796', 1, 'Avengers: Endgame', 'After the devastating events of Avengers: Infinity War (2018), the universe is in ruins due to the efforts of the Mad Titan, Thanos. With the help of remaining allies, the Avengers must assemble once more in order to undo Thanos\'s actions and undo the chaos to the universe, no matter what consequences may be in store, and no matter who they face...', 'https://m.media-amazon.com/images/M/MV5BMTc5MDE2ODcwNV5BMl5BanBnXkFtZTgwMzI2NzQ2NzM@._V1_SX300.jpg', '8.4', '2019'),
(4, 'tt6146586', 1, 'John Wick: Chapter 3 - Parabellum', 'In this third installment of the adrenaline-fueled action franchise, skilled assassin John Wick (Keanu Reeves) returns with a $14 million price tag on his head and an army of bounty-hunting killers on his trail. After killing a member of the shadowy international assassin\'s guild, the High Table, John Wick is excommunicado, but the world\'s most ruthless hit men and women await his every turn.', 'https://m.media-amazon.com/images/M/MV5BYjdlNWFlZjEtM2U0NS00ZWU5LTk1M2EtZmQxNWFiZjk0MGM5XkEyXkFqcGc@._V1_SX300.jpg', '7.4', '2019'),
(5, 'tt8936646', 1, 'Extraction', 'In an underworld of weapons dealers and traffickers, a young boy becomes the pawn in a war between notorious drug lords. Trapped by kidnappers inside one of the world\'s most impenetrable cities, his rescue beckons the unparalleled skill of a mercenary named Tyler Rake, but Rake is a broken man with nothing to lose, harboring a death wish that makes an already deadly mission near impossible.', 'https://m.media-amazon.com/images/M/MV5BNDBhMmI3OWYtZTA2Ny00Y2RjLTliMWQtYWY5MGIwN2RlZGFjXkEyXkFqcGc@._V1_SX300.jpg', '6.8', '2020'),
(6, 'tt6723592', 1, 'Tenet', 'In a twilight world of international espionage, an unnamed CIA operative, known as The Protagonist, is recruited by a mysterious organization called Tenet to participate in a global assignment that unfolds beyond real time. The mission: prevent Andrei Sator, a renegade Russian oligarch with precognition abilities, from starting World War III. The Protagonist will soon master the art of \"time inversion\" as a way of countering the threat that is to come.', 'https://m.media-amazon.com/images/M/MV5BNTIzNDIxMzktMzlkMi00MmUyLWFmMjQtZDgwMjBmOGJmNTI3XkEyXkFqcGc@._V1_SX300.jpg', '7.3', '2020'),
(7, 'tt9376612', 1, 'Shang-Chi and the Legend of the Ten Rings', 'Shang-Chi, the master of weaponry-based Kung Fu, is forced to confront his past after being drawn into the Ten Rings organization.', 'https://m.media-amazon.com/images/M/MV5BZmY5MDcyNzAtYzg3MC00MGNlLTg3OGItNmRjYThkZGVlNzAyXkEyXkFqcGc@._V1_SX300.jpg', '7.3', '2021'),
(8, 'tt2382320', 1, 'No Time to Die', 'Bond has left active service and is enjoying a tranquil life in Jamaica. His peace is short-lived when his old friend Felix Leiter from the CIA turns up asking for help. The mission to rescue a kidnapped scientist turns out to be far more treacherous than expected, leading Bond onto the trail of a mysterious villain armed with dangerous new technology.', 'https://m.media-amazon.com/images/M/MV5BZGZiOGZhZDQtZmRkNy00ZmUzLTliMGEtZGU0NjExOGMxZDVkXkEyXkFqcGc@._V1_SX300.jpg', '7.3', '2021'),
(9, 'tt1745960', 1, 'Top Gun: Maverick', 'The story involves Maverick confronting his past while training a group of younger Top Gun graduates, including the son of his deceased best friend, for a dangerous mission.', 'https://m.media-amazon.com/images/M/MV5BMDBkZDNjMWEtOTdmMi00NmExLTg5MmMtNTFlYTJlNWY5YTdmXkEyXkFqcGc@._V1_SX300.jpg', '8.2', '2022'),
(10, 'tt1877830', 1, 'The Batman', 'When a sadistic serial killer begins murdering key political figures in Gotham, the Batman is forced to investigate the city\'s hidden corruption and question his family\'s involvement.', 'https://m.media-amazon.com/images/M/MV5BMmU5NGJlMzAtMGNmOC00YjJjLTgyMzUtNjAyYmE4Njg5YWMyXkEyXkFqcGc@._V1_SX300.jpg', '7.8', '2022'),
(12, 'tt9603212', 1, 'Mission: Impossible - Dead Reckoning Part One', 'Ethan Hunt and his IMF team must track down a dangerous weapon before it falls into the wrong hands.', 'https://m.media-amazon.com/images/M/MV5BN2U4OTdmM2QtZTkxYy00ZmQyLTg2N2UtMDdmMGJmNDhlZDU1XkEyXkFqcGc@._V1_SX300.jpg', '7.6', '2023'),
(13, 'tt6791350', 1, 'Guardians of the Galaxy Vol. 3', 'Still reeling from the loss of Gamora, Peter Quill rallies his team to defend the universe and one of their own - a mission that could mean the end of the Guardians if not successful.', 'https://m.media-amazon.com/images/M/MV5BOTJhOTMxMmItZmE0Ny00MDc3LWEzOGEtOGFkMzY4MWYyZDQ0XkEyXkFqcGc@._V1_SX300.jpg', '7.9', '2023'),
(14, 'tt3104988', 2, 'Crazy Rich Asians', 'Rachel Chu, an American-born Chinese NYU professor, travels with her boyfriend, Nick to his hometown of Singapore for his best friend\'s wedding. Before long, his secret is out: Nick\'s family is wealthy, and he\'s considered the most eligible bachelor in Asia. Every single woman is incredibly jealous of Rachel and wants to bring her down.', 'https://m.media-amazon.com/images/M/MV5BMTYxNDMyOTAxN15BMl5BanBnXkFtZTgwMDg1ODYzNTM@._V1_SX300.jpg', '6.9', '2018'),
(15, 'tt2704998', 2, 'Game Night', 'Married competitive gamer couple Max and Annie are trying to have a child, but their attempts are unsuccessful due to Max\'s stress surrounding his feelings of inadequacy when compared to his successful, attractive brother Brooks. During Max and Annie\'s routine weekend game night with their friends Ryan, and married couple Kevin and Michelle, Brooks shows up Max by arriving in a Corvette Stingray (Max\'s dream car) and sharing an embarrassing childhood story about Max. Brooks offers to host the next game night at a house he\'s renting while he\'s in town. Meanwhile, Max and Annie are trying to keep their game nights secret from their neighbor Gary, an awkward police officer after his divorce from their friend Debbie..', 'https://m.media-amazon.com/images/M/MV5BMjI3ODkzNDk5MF5BMl5BanBnXkFtZTgwNTEyNjY2NDM@._V1_SX300.jpg', '6.9', '2018'),
(16, 'tt2584384', 2, 'Jojo Rabbit', 'A World War II satire that follows a lonely German boy named Jojo (Roman Griffin Davis) whose world view is turned upside down when he discovers his single mother (Scarlett Johansson) is hiding a young Jewish girl (Thomasin McKenzie) in their attic. Aided only by his idiotic imaginary friend, Adolf Hitler (Taika Waititi), Jojo must confront his blind nationalism.', 'https://m.media-amazon.com/images/M/MV5BYmFmNmUyMjYtZTFjNS00OWQyLThhZmMtMGZkYTQ3YjY0ZDQ1XkEyXkFqcGc@._V1_SX300.jpg', '7.9', '2019'),
(17, 'tt8946378', 2, 'Knives Out', 'When renowned crime novelist Harlan Thrombey (Christopher Plummer) is found dead at his estate just after his 85th birthday, the inquisitive and debonair Detective Benoit Blanc (Daniel Craig) is mysteriously enlisted to investigate. From Harlan\'s disfunctional family to his devoted staff, Blanc sifts through a web of red herrings and self-serving lies to uncover the truth behind Harlan\'s untimely death.', 'https://m.media-amazon.com/images/M/MV5BZDU5ZTRkYmItZjg0Mi00ZTQwLThjMWItNWM3MTMxMzVjZmVjXkEyXkFqcGc@._V1_SX300.jpg', '7.9', '2019'),
(18, 'tt9484998', 2, 'Palm Springs', 'While stuck at a wedding in Palm Springs, Nyles (Andy Samberg) meets Sarah (Cristin Milioti), the maid of honor and family black sheep. After he rescues her from a disastrous toast, Sarah becomes drawn to Nyles and his offbeat nihilism. But when their impromptu tryst is thwarted by a surreal interruption, Sarah must join Nyles in embracing the idea that nothing really matters, and they begin wreaking spirited havoc on the wedding celebration.', 'https://m.media-amazon.com/images/M/MV5BY2VkNGY0MTMtMjEzZi00OThkLWJiOTMtNGU4ZGNjZDE5ZGIyXkEyXkFqcGc@._V1_SX300.jpg', '7.4', '2020'),
(19, 'tt10161886', 2, 'The Prom', 'In desperate need of a noble cause to revive their public images and bounce back, self-centred Broadway thespians, Dee Dee Allen and Barry Glickman, the narcissistic stars of Eleanor, The Eleanor Roosevelt Story, have come up with a foolproof plan to earn some positive publicity. As a result, to help Emma, a bright-eyed high-school student who has recently come out of the closet, make her dream come true, the flamboyant celebrity activists and their fellow struggling actors, Angie Dickinson and Trent Oliver, head to the small town of Edgewater, Indiana, to right a wrong. But, Emma only wants to take her girlfriend to the prom, and intolerance stands in the way of acceptance and happiness. Can the bold quartet give Emma the prom she deserves?', 'https://m.media-amazon.com/images/M/MV5BY2ViN2NkY2QtNTU2Yy00MjE0LTgxZWEtMmNmYTZmOTU4ODJjXkEyXkFqcGc@._V1_SX300.jpg', '5.9', '2020'),
(20, 'tt6264654', 2, 'Free Guy', 'In the extremely popular video game, Free City, a NPC named Guy learns the true nature of his existence when he meets the girl of his dreams, a human player. This player\'s interactions with Guy has massive affects on him, the game, and real world as they play it.', 'https://m.media-amazon.com/images/M/MV5BN2I0MGMxYjUtZTZiMS00MzMxLTkzNWYtMDUyZmUwY2ViYTljXkEyXkFqcGc@._V1_SX300.jpg', '7.1', '2021'),
(21, 'tt11286314', 2, 'Don\'t Look Up', 'Kate Dibiasky (Jennifer Lawrence), an astronomy grad student, and her professor Dr. Randall Mindy (Leonardo DiCaprio) make an astounding discovery of a comet orbiting within the solar system. The problem - it\'s on a direct collision course with Earth. The other problem? No one really seems to care. Turns out warning mankind about a planet-killer the size of Mount Everest is an inconvenient fact to navigate. With the help of Dr. Oglethorpe (Rob Morgan), Kate and Randall embark on a media tour that takes them from the office of an indifferent President Orlean (Meryl Streep) and her sycophantic son and Chief of Staff, Jason (Jonah Hill), to the airwaves of The Daily Rip, an upbeat morning show hosted by Brie (Cate Blanchett) and Jack (Tyler Perry). With only six months until the comet makes impact, managing the 24-hour news cycle and gaining the attention of the social media obsessed public before it\'s too late proves shockingly comical - what will it take to get the world to just look up?.', 'https://m.media-amazon.com/images/M/MV5BMjhhNWFjNzctYTJjOS00MDc0LThiNjItZmM0ZDVmMWViY2UzXkEyXkFqcGc@._V1_SX300.jpg', '7.2', '2021'),
(22, 'tt6710474', 2, 'Everything Everywhere All at Once', 'A middle-aged Chinese immigrant is swept up into an insane adventure in which she alone can save existence by exploring other universes and connecting with the lives she could have led.', 'https://m.media-amazon.com/images/M/MV5BOWNmMzAzZmQtNDQ1NC00Nzk5LTkyMmUtNGI2N2NkOWM4MzEyXkEyXkFqcGc@._V1_SX300.jpg', '7.8', '2022'),
(23, 'tt11564570', 2, 'Glass Onion', 'Tech billionaire Miles Bron invites his friends for a getaway on his private Greek island. When someone turns up dead, Detective Benoit Blanc is put on the case.', 'https://m.media-amazon.com/images/M/MV5BMzI2ZDYxZTEtMzVlOC00OTUyLTgyNTAtYWFhNmRhZjAzZWE1XkEyXkFqcGc@._V1_SX300.jpg', '7.1', '2022'),
(24, 'tt1517268', 2, 'Barbie', 'Barbie and Ken are having the time of their lives in the seemingly perfect world of Barbie Land. However, when they get a chance to go to the outside world, they soon discover the joys and perils of living among regular humans.', 'https://m.media-amazon.com/images/M/MV5BYjI3NDU0ZGYtYjA2YS00Y2RlLTgwZDAtYTE2YTM5ZjE1M2JlXkEyXkFqcGc@._V1_SX300.jpg', '6.8', '2023'),
(25, 'tt17527468', 2, 'Bottoms', 'Two unpopular queer high-school students start a fight club to have sex before graduation.', 'https://m.media-amazon.com/images/M/MV5BNzEyNTNlNDAtNTMxOC00YzMzLWFkM2QtZmRiNGE5ZTQyMWFmXkEyXkFqcGc@._V1_SX300.jpg', '6.7', '2023'),
(26, 'tt6644200', 4, 'A Quiet Place', 'In a devastated Earth overrun by invincible predators of a possible extraterrestrial origin, the Abbotts find themselves struggling to survive in the isolation of upstate New York, defined by a new era of utter silence. Indeed, as this new type of invader is attracted to noise, even the slightest of sounds can be deadly; however, it\'s been already twelve months since the powerful monsters\' first sightings, and this resilient family still stands strong. Of course, learning the rules of survival in this muted dystopia is essential; nevertheless, now, of all times, an otherwise joyous event puts in jeopardy the already fragile stability. And now, more than ever, the Abbotts must not make a sound.', 'https://m.media-amazon.com/images/M/MV5BMjI0MDMzNTQ0M15BMl5BanBnXkFtZTgwMTM5NzM3NDM@._V1_SX300.jpg', '7.5', '2018'),
(27, 'tt7784604', 4, 'Hereditary', 'When her mentally ill mother passes away, Annie (Toni Collette), her husband (Gabriel Byrne), son (Alex Wolff), and daughter (Milly Shapiro) all mourn her loss. The family turn to different means to handle their grief, including Annie and her daughter both flirting with the supernatural. They each begin to have disturbing, otherworldly experiences linked to the sinister secrets and emotional trauma that have been passed through the generations of their family.', 'https://m.media-amazon.com/images/M/MV5BNTEyZGQwODctYWJjZi00NjFmLTg3YmEtMzlhNjljOGZhMWMyXkEyXkFqcGc@._V1_SX300.jpg', '7.3', '2018'),
(28, 'tt6857112', 4, 'Us', 'In order to get away from their busy lives, the Wilson family takes a vacation to Santa Cruz, California with the plan of spending time with their friends, the Tyler family. On a day at the beach, their young son Jason almost wanders off, causing his mother Adelaide to become protective of her family. That night, four mysterious people break into Adelaide\'s childhood home where they\'re staying. The family is shocked to find out that the intruders look like them, only with grotesque appearances.', 'https://m.media-amazon.com/images/M/MV5BMzhkMjFkN2YtODU2Ni00YWYwLWExN2MtOWNjZmQxM2U4YTM5XkEyXkFqcGc@._V1_SX300.jpg', '6.8', '2019'),
(29, 'tt8772262', 4, 'Midsommar', 'Dani (Florence Pugh) and Christian (Jack Reynor) are a young American couple with a relationship on the brink of falling apart. But after a family tragedy keeps them together, Christian invites a grieving Dani to join him and his friends on a trip to a once-in-a-lifetime midsummer festival in a remote Swedish village. What begins as a carefree summer holiday in the North European land of eternal sunlight takes a sinister turn when the insular villagers invite their guests to partake in festivities that render the pastoral paradise increasingly unnerving and viscerally disturbing.', 'https://m.media-amazon.com/images/M/MV5BMzQxNzQzOTQwM15BMl5BanBnXkFtZTgwMDQ2NTcwODM@._V1_SX300.jpg', '7.1', '2019'),
(30, 'tt1051906', 4, 'The Invisible Man', 'The film follows Cecilia, who receives the news of her abusive ex-boyfriend\'s suicide. She begins to re-build her life for the better. However, her sense of reality is put into question when she begins to suspect her deceased lover is not actually dead.', 'https://m.media-amazon.com/images/M/MV5BYTM3NDJhZWUtZWM1Yy00ODk4LThjNmMtNDg3OGYzMGM2OGYzXkEyXkFqcGc@._V1_SX300.jpg', '7.1', '2020'),
(31, 'tt8508734', 4, 'His House', 'A refugee couple makes a harrowing escape from war-torn South Sudan, but then they struggle to adjust to their new life in an English town that has an evil lurking beneath the surface.', 'https://m.media-amazon.com/images/M/MV5BMDBjNmYxMjctMjViNC00NmRlLWJkZWYtOTE2ZmY4ZDg3NmMyXkEyXkFqcGc@._V1_SX300.jpg', '6.4', '2020'),
(32, 'tt8332922', 4, 'A Quiet Place Part II', 'With the newly acquired knowledge of the seemingly invulnerable creatures\' weakness, grief-stricken Evelyn Abbott finds herself on her own, with two young teens, a defenceless newborn son, and with no place to hide. Now, 474 days after the all-out alien attack in A Quiet Place (2018), the Abbotts summon up every last ounce of courage to leave their now-burned-to-the-ground farm and embark on a peril-laden quest to find civilization. With this in mind, determined to expand beyond the boundaries, the resilient survivors have no other choice but to venture into eerily quiet, uncharted hostile territory, hoping for a miracle. But, this time, the enemy is everywhere.', 'https://m.media-amazon.com/images/M/MV5BNjRiYjk4ZmItNGQ5NS00MmRhLTk4Y2EtMGQ1MTYxZWJhYjU0XkEyXkFqcGc@._V1_SX300.jpg', '7.2', '2020'),
(33, 'tt3811906', 4, 'Malignant', 'Twenty-seven long years after the brutal Simion Research Hospital incident, abused Madison wakes up in a hospital in present-day Seattle. But with numbing visions of murder getting in the way of a normal life, more and more, Madison\'s obscure past emerges, baffling both herself and the local detectives. Are these explicitly violent killings figments of Madison\'s troubled imagination? Either way, someone, or better yet, something, links the past to the present, demanding closure and blood. Is the bogeyman real?', 'https://m.media-amazon.com/images/M/MV5BNTU3YjhmMTQtNmM5Yi00MDI2LTk0M2UtZGY5ZTNkYjIzNDE3XkEyXkFqcGc@._V1_SX300.jpg', '6.2', '2021'),
(34, 'tt15474916', 4, 'Smile', 'After witnessing a bizarre, traumatic incident involving a patient, a psychiatrist becomes increasingly convinced she is being threatened by an uncanny entity.', 'https://m.media-amazon.com/images/M/MV5BNjFhMzBlNzktMjE2Ni00YTMyLWI2YWUtYmM1N2QxMDQwZmZhXkEyXkFqcGc@._V1_SX300.jpg', '6.5', '2022'),
(35, 'tt13560574', 4, 'X', 'Set in 1979, adult movie actors and a small film crew arrive to a farmhouse occupied by an elderly couple in the desolate Texas countryside to film an adult movie. As the day shifts to night, the visitors slowly realize that they are not safe, and are being targeted by a nearby enemy.', 'https://m.media-amazon.com/images/M/MV5BODUwYTNhMTMtYWQ5Ny00YTdmLWIxOTAtNDczNzVlYzg2NDFkXkEyXkFqcGc@._V1_SX300.jpg', '6.5', '2022'),
(36, 'tt10638522', 4, 'Talk to Me', 'When a group of friends discover how to conjure spirits using an embalmed hand, they become hooked on the new thrill, until one of them goes too far and unleashes terrifying supernatural forces.', 'https://m.media-amazon.com/images/M/MV5BY2I2NzJmY2YtYTM3Ni00ZGJhLThkZTItODFhMzhlZjZkMDQ5XkEyXkFqcGc@._V1_SX300.jpg', '7.1', '2022'),
(37, 'tt8760708', 4, 'M3GAN', 'When robotics engineer Gemma becomes the guardian of her orphaned niece, Cady, she thinks her new invention, a robotic AI, will be a good companion. However, M3GAN begins to behave in unexpected and shocking ways.', 'https://m.media-amazon.com/images/M/MV5BYjU1ZWMxYTUtNzQ1ZC00ZTcxLTg0NTMtMzY1ZmQyZjhmYjMyXkEyXkFqcGc@._V1_SX300.jpg', '6.3', '2022'),
(38, 'tt3846674', 3, 'To All the Boys I\'ve Loved Before', 'Lara Jean Covey writes letters to all of her past loves, the letters are meant for her eyes only. Until one day when all the love letters are sent out to her previous loves. Her life is soon thrown into chaos when her foregoing loves confront her one by one.', 'https://m.media-amazon.com/images/M/MV5BMjQ3NjM5MTAzN15BMl5BanBnXkFtZTgwODQzMDAwNjM@._V1_SX300.jpg', '7.0', '2018'),
(39, 'tt5164432', 3, 'Love, Simon', 'A young coming-of-age tale about a teenage boy, Simon Spier, goes through a different kind of Romeo and Juliet story. Simon has a love connection with a boy, Blue, by email, but the only problem is that Simon has no idea who he\'s talking to. Simon must discover who that boy is--who Blue is. Along the way, he tries to find himself as well.', 'https://m.media-amazon.com/images/M/MV5BN2ViMjNkMDMtY2MwZC00YzRiLWJlY2UtNjU0YzNmMDc3NzkxXkEyXkFqcGc@._V1_SX300.jpg', '7.5', '2018'),
(40, 'tt7653254', 3, 'Marriage Story', 'MARRIAGE STORY is Academy Award nominated filmmaker Noah Baumbach\'s incisive and compassionate look at a marriage breaking up and a family staying together. The film stars Scarlett Johansson and Adam Driver. Laura Dern, Alan Alda, and Ray Liotta co-star.', 'https://m.media-amazon.com/images/M/MV5BNmE0OWJlM2MtNzhmMi00YmQyLTlmY2EtZmUzNzBiNGRlN2JkXkEyXkFqcGc@._V1_SX300.jpg', '7.9', '2019'),
(41, 'tt8637428', 3, 'The Farewell', 'A headstrong Chinese-American woman returns to China when her beloved grandmother is diagnosed with terminal cancer. Billi struggles with her family\'s decision to keep grandma in the dark about her own illness as they all stage an impromptu wedding to see grandma one last time.', 'https://m.media-amazon.com/images/M/MV5BYjdlZDI1OWYtZWU4MC00NDhkLThmOWEtMTZiODFhZThkMjA4XkEyXkFqcGc@._V1_SX300.jpg', '7.5', '2019'),
(42, 'tt9683478', 3, 'The Half of It', 'A shy, introverted, Chinese-American, straight-A student finds herself helping the school jock woo the girl they both secretly love. In the process, each teaches the other about the nature of love as they find connection in the most unlikely of places.', 'https://m.media-amazon.com/images/M/MV5BN2Y0NWUzNmEtMDM4My00ODQyLTg1NGItOGViYjg0MzNlYTQwXkEyXkFqcGc@._V1_SX300.jpg', '6.9', '2020'),
(43, 'tt9214832', 3, 'Emma.', 'Jane Austen\'s beloved comedy about finding your equal and earning your happy ending, is reimagined in this. Handsome, clever, and rich, Emma Woodhouse is a restless queen bee without rivals in her sleepy little town. In this glittering satire of social class and the pain of growing up, Emma must adventure through misguided matches and romantic missteps to find the love that has been there all along.', 'https://m.media-amazon.com/images/M/MV5BZDdkZjg3YWYtMjMyNS00NmM0LTk4NWItMzA0MWYxN2U0MWY5XkEyXkFqcGc@._V1_SX300.jpg', '6.7', '2020'),
(44, 'tt12889404', 3, 'Cyrano', 'A man ahead of his time, Cyrano de Bergerac dazzles whether with ferocious wordplay at a verbal joust or with brilliant swordplay in a duel. But, convinced that his appearance renders him unworthy of the love of a devoted friend, the luminous Roxanne, Cyrano has yet to declare his feelings for her and Roxanne has fallen in love, at first sight, with Christian.', 'https://m.media-amazon.com/images/M/MV5BYzdjZDU0NDMtNTFhYy00NGNiLTlkMzQtNjNiNWY3Y2IyYjM5XkEyXkFqcGc@._V1_SX300.jpg', '6.4', '2021'),
(45, 'tt10370710', 3, 'The Worst Person in the World', 'A modern dramedy about the quest for love and meaning in contemporary Oslo. It chronicles four years in the life of Julie, a young woman who navigates the troubled waters of her love life and struggles to find her career path, leading her to take a realistic look at who she really is.', 'https://m.media-amazon.com/images/M/MV5BZGEyYzBiYmItZDM4OC00NTdmLWJlYzctODdiM2E2MjZmYTU2XkEyXkFqcGc@._V1_SX300.jpg', '7.7', '2021'),
(46, 'tt13320622', 3, 'The Lost City', 'A reclusive romance novelist on a book tour with her cover model gets swept up in a kidnapping attempt that lands them both in a cutthroat jungle adventure.', 'https://m.media-amazon.com/images/M/MV5BZjA4YmZjMWItZGNkNS00ODFkLWEwNjUtNGNhMzViZDRmMzgxXkEyXkFqcGc@._V1_SX300.jpg', '6.1', '2022'),
(47, 'tt15218000', 3, 'Fire Island', 'A group of queer best friends gather in Fire Island Pines for their annual week of love and laughter, but a sudden change of events might make this their last summer in gay paradise.', 'https://m.media-amazon.com/images/M/MV5BMGNjOGE5ZjgtZjA1ZC00ZTgyLTg0M2MtNGEzM2VkMTQ2NzQzXkEyXkFqcGc@._V1_SX300.jpg', '6.7', '2022'),
(48, 'tt13238346', 3, 'Past Lives', 'Nora and Hae Sung, two deeply connected childhood friends, are wrested apart after Nora\'s family emigrates from South Korea. Twenty years later, they are reunited for one fateful week as they confront notions of love and destiny.', 'https://m.media-amazon.com/images/M/MV5BYjQyMTNhNjUtN2VmYy00NWRhLTkwOTctMGVmNTBmNDIxYjZhXkEyXkFqcGc@._V1_SX300.jpg', '7.8', '2023'),
(49, 'tt15789038', 3, 'Elemental', 'Follows Ember and Wade, in a city where fire-, water-, earth- and air-residents live together.', 'https://m.media-amazon.com/images/M/MV5BNjU2MjE1OGItZjdmYS00ZmIyLTljNjYtOWI5ZGRkZjM4NDEwXkEyXkFqcGc@._V1_SX300.jpg', '7.0', '2023');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `imdbID` varchar(20) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_text` text NOT NULL,
  `is_hidden` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `imdbID`, `rating`, `review_text`, `is_hidden`, `created_at`, `updated_at`) VALUES
(2, 1, 'tt8508734', 2, 'Testing 1 2', 0, '2025-11-23 15:52:56', '2025-11-23 15:52:56'),
(3, 1, 'tt7653254', 5, 'beautiful story!', 0, '2025-11-23 16:14:53', '2025-11-23 16:14:53'),
(4, 1, 'tt2584384', 5, 'funny haha!', 0, '2025-11-23 19:03:38', '2025-11-23 19:03:53'),
(5, 1, 'tt6857112', 5, 'nice amazing', 1, '2025-11-24 17:03:05', '2025-11-24 17:03:23'),
(6, 1, 'tt6857112', 2, 'meh', 0, '2025-11-24 17:13:38', '2025-11-24 17:14:52'),
(7, 2, 'tt2584384', 5, 'great!', 0, '2025-11-24 17:24:01', '2025-11-24 17:24:01'),
(8, 1, 'tt6857112', 3, 'hhh', 1, '2025-11-24 22:15:13', '2025-11-24 22:15:40'),
(9, 1, 'tt6857112', 3, 'jj', 1, '2025-11-24 22:15:31', '2025-11-24 22:15:44'),
(10, 2, 'tt6857112', 1, 'bad', 0, '2025-11-26 16:32:42', '2025-11-26 16:32:42'),
(11, 8, 'tt10370710', 5, 'Amazing movie, must see!', 0, '2025-11-26 19:27:19', '2025-11-26 19:31:56'),
(12, 10, 'tt6857112', 3, 'nice', 1, '2025-11-26 21:01:09', '2025-11-26 21:18:41'),
(13, 2, 'tt3811906', 4, 'test', 0, '2025-11-26 22:04:55', '2025-11-26 22:04:55'),
(14, 14, 'tt4912910', 5, 'this is a detailed review', 0, '2025-11-27 18:34:53', '2025-11-27 18:34:53'),
(15, 2, 'tt8772262', 1, 'sad', 0, '2025-11-30 19:52:18', '2025-11-30 19:52:18'),
(16, 16, 'tt17527468', 5, 'nice', 0, '2025-12-03 19:21:52', '2025-12-03 19:21:52'),
(17, 8, 'tt2584384', 3, 'nce', 0, '2025-12-04 16:06:06', '2025-12-04 16:06:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `remember_token` varchar(64) DEFAULT NULL,
  `remember_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`, `remember_token`, `remember_expires`) VALUES
(1, 'meera', 'amiratafolabi25@gmail.com', '$2y$10$bv0huDKSCBZyiMLTTKCtdui.rcrakqhiDs7sseGNS7UAZDAHN0K1m', '2025-11-23 13:19:58', NULL, NULL),
(2, 'luca', 'luca@mail.com', '$2y$10$NVnTd8jMPKq5LCod5OK8TeVruCJxuNb0ofzrW4X6mcsqzyK6JbeUS', '2025-11-23 17:39:39', NULL, NULL),
(3, 'dan', 'dan122@gmail.com', '$2y$10$PfpcqmqllrJ3DNaKBIqOp.cJXaqyj6P29Q2sHHPEy1/msHUrIeyBW', '2025-11-24 22:50:01', NULL, NULL),
(4, 'user4', 'user@mail.com', '$2y$10$8Er9eazS5hjbUkSMhFSiIuGw7o6lxTCRbIoM2F4/Q4TrUulix7RC.', '2025-11-24 22:53:14', NULL, NULL),
(5, 'janedoe', 'janedoe@mail.com', '$2y$10$1yvSTZguFclo7LarDXWVyuzP3bnzZNBUTOmRHUrWxMsKlNkwVIo4O', '2025-11-24 22:55:43', NULL, NULL),
(6, 'helen', 'helen@mail.com', '$2y$10$9aDduUZD1l7dkmk2XVkfiO21mEJ5sRI9nMtVU7Pva5QW3nk1/VdDu', '2025-11-24 22:58:20', NULL, NULL),
(7, 'tiwa', 'tiwa@mail.com', '$2y$10$sLHF08vg11b6J.8Zd5b4LeOUQItQoRntR4.RN2z/m8noBpOwAsRfW', '2025-11-24 23:00:54', NULL, NULL),
(8, 'lawunmi', 'la@gmail.com', '$2y$10$4hUurQ6is0OY.6xeexHfkuI1OLChJ09n0A7A6FkX3yKI6V1BBcTmG', '2025-11-24 23:16:50', NULL, NULL),
(9, 'ppmoney', 'pp@gmail.com', '$2y$10$eBp5RL4Tr7kCtpLe6uE/4.5teVtiFTFIbxVThn7/8bZisnF/r0iS.', '2025-11-24 23:18:51', NULL, NULL),
(10, 'kufo', 'kkk@gmail.com', '$2y$10$41Pi.cdOeB1ThDbpMJmCN.fllRMgJCGAwE/dHjsI2xf7Erb81yqxq', '2025-11-26 19:33:18', NULL, NULL),
(11, 'melissa', 'mel@mail.com', '$2y$10$QeLdGVP0ueXj4xe3HA5ehuyk3YD7QE9792Fpbe0uXLjE4QtV8dBFi', '2025-11-26 22:28:12', NULL, NULL),
(12, 'bat', 'bat@mail.com', '$2y$10$BZmo9uGuXLIcyewqHkgXkeu3MOyd5BeHYgoOreZ/w9g4MAZCgijmi', '2025-11-26 22:29:08', NULL, NULL),
(13, 'mia', 'mia@mail.com', '$2y$10$hCEVYjWAVeadhe3/X3Ca3eaVcXzbz/0fFkG2zEEQUP.58JpNd9LMC', '2025-11-26 23:02:14', NULL, NULL),
(14, 'steve', 'steve@gmail.com', '$2y$10$6L.asa6PPORdWEA00s0PeeRJV3c8gfWQjP7gyzojUnC3dWe4woaJW', '2025-11-27 18:29:56', NULL, NULL),
(15, 'mall', 'mal@mail.com', '$2y$10$va.sNiXSDLHctCHMk6dxNuJur.OffKKnnfuy9Et1tAG6ygyJt3MFS', '2025-11-30 08:05:59', NULL, NULL),
(16, 'andy', 'andy@mail.com', '$2y$10$m3WjaH6i3r9WdxeqAsyXLOF8VNQT2Eu9QwCn7dDjhSlnb6QadIFNC', '2025-12-03 19:20:43', NULL, NULL),
(17, 'hannah', 'han@gmail.com', '$2y$10$p9VY8JuXtr25rAQKqox/.u5R0M8MUYivtYTgB4v3znerUX6IRjCL.', '2025-12-04 16:11:29', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`banner_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`),
  ADD UNIQUE KEY `imdbID` (`imdbID`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_reviews_movie` (`imdbID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_remember_token` (`remember_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `banner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=334;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_movie` FOREIGN KEY (`imdbID`) REFERENCES `movies` (`imdbID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
