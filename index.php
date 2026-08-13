<?php
    $dev = true;
    $staging = false;
    $database_link = '../php/database.php';
    if ($dev) $database_link = './php/sqlite/connect.php';
    if ($staging) $database_link = '../../php/database.php';
    require_once($database_link);
        $user_choice = (int) htmlspecialchars($_POST['choice']);
        $poll_num = 1;
        $select = "SELECT * FROM vote
                   WHERE id = $poll_num";
        $row = $pdo->query($select)->fetchAll()[0];
        if (!is_null($row["tally{$user_choice}"])) {
            $update = "UPDATE vote 
                       SET tally{$user_choice} = tally{$user_choice} + 1 
                       WHERE id = $poll_num;";
            $pdo->exec($update);
        }
        $row["tally{$user_choice}"] += 1;

        $choices = explode(",", $row['choices']);
        $tallies = ['numbers' => []];
        $i = 0;
        foreach ($row as $col => $val) {
            if (!str_contains($col, 'tally'))
                continue;
            if (is_null($val))
                continue;
            $tallies[$i] = [ 'tally' => $val ];
            array_push($tallies['numbers'], $val);
            $i++;
        }
        $i = 0;
        foreach ($choices as $choice) {
            $tallies[$i]['choice'] = $choice;
            $i++;
        }
        $num_choices = $i;

        $tally_sum = array_sum($tallies['numbers']);
        $tally_max = max($tallies['numbers']);
    }
?><!DOCTYPE html>
<!-- JM, 05/07/2026 -->
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>&lt;JM&gt; - Home</title>

        <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="./assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./assets/favicon/favicon-16x16.png">
        <link rel="manifest" type="application/manifest+json" href="./assets/favicon/site.webmanifest">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Electrolize&family=Ubuntu:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
        <link rel="preload" href="./assets/fonts/corporate-logo-rounded-bold-ver3.woff2" as="font" type="font/woff2" crossorigin>

        <link rel="stylesheet" type="text/css" href="./css/styles.css">
        <script src="./js/inception.js"></script>
        <script src="./js/p5.min.js" class="p5-script"></script>

        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Person",
                "name": "Jeremy Meyers",
                "url": "https://jeremymeyers.dev/",
                "image": "https://jeremymeyers.dev/WEB290/assets/images/JM-tag-logo.svg",
                "email": "jeremy@jeremymeyers.dev",
                "nationality": "American",
                "gender": "Male",
                "alumniOf": {
                    "@type": "EducationalOrganization",
                    "name": "Johnson County Community College",
                    "sameAs": "https://www.jccc.edu/"
                },
                "knowsAbout": [
                    "HTML",
                    "CSS",
                    "JavaScript",
                    "Python",
                    "Ruby",
                    "jQuery",
                    "Web Developement",
                    "Front-end Web Development"
                ],
                "description": "Web Developer",
                "sameAs": [
                    "https://github.com/sharkleadership",
                    "https://www.linkedin.com/in/jeremy-meyers-428811368/"
                ]
            }
        </script>

        <script src="./js/scripts.js" defer></script>
        <script src="./js/sketches.js" class="p5-script" defer></script>
    </head>
    <body>
        <div class="wrapper">
            <header class="header full-bleed">
                <nav>
                    <label for="hamburger--header">
                        <input type="checkbox" id="hamburger--header" class="hamburger hamburger--header">
                        <div class="hamburger__button" aria-label="Open Menu" role="button" tabindex="0">
                            <span class="bar bar--top"></span> <!-- end .bar -->
                            <span class="bar bar--middle"></span> <!-- end .bar -->
                            <span class="bar bar--bottom"></span> <!-- end .bar -->
                        </div> <!-- end .hamburger__button -->
                    </label>
                    <menu>
                        <li><a href="#about" class="icon-before--about">About</a></li>
                        <li><a href="#projects" class="icon-before--projects">Projects</a></li>
                        <li><a href="#contact" class="icon-before--contact">Contact</a></li>
                        <li><a href="#vote" class="icon-before--vote--dark">Vote</a></li>
                    </menu>
                    <h1 class="JM-logo">
                        <a href="<?=$_SERVER['PHP_SELF']?>" tabindex="0">
                            <svg
                                viewBox="0 0 67.733329 20.720588"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                role="img"
                                aria-label="<JM> logo.">
                                <g
                                    class="JM-logo__jm-group"
                                    transform="matrix(0.09170011,0,0,0.09170313,-1.3426703,-13.027762)">
                                    <path
                                    style="fill:none;stroke:#1e90ff;stroke-width:32.6422;stroke-linecap:round;stroke-linejoin:round;stroke-opacity:1;paint-order:normal"
                                    d="m 294.51542,198.82659 c 57.75099,60.67636 -11.33134,218.87243 -108.91981,114.48594"
                                    class="JM-logo__j" />
                                    <path
                                    style="display:inline;fill:none;stroke:#1e90ff;stroke-width:32.6111;stroke-linecap:round;stroke-linejoin:round;stroke-opacity:1;paint-order:fill markers stroke"
                                    d="m 170.47387,211.35078 c 63.60558,-4.66523 147.90373,-43.13442 222.04248,-52.86148 17.67003,-1.51476 19.84077,8.54425 20.47473,14.64474 -2.33237,15.89532 -45.81636,65.29105 -55.78886,86.49105 25.7831,-15.05333 79.32976,-72.26693 121.58501,-72.92684 0.28109,14.2211 -14.3575,58.96575 5.04779,62.14087 48.7994,-3.72238 118.17024,-24.07337 111.74624,9.77557 l -21.97146,93.09728"
                                    class="JM-logo__m" />
                                    <path
                                    style="fill:none;fill-rule:evenodd;stroke:#1e90ff;stroke-width:6.47914;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;paint-order:fill markers stroke"
                                    d="m 570.09582,355.59595 c 29.53052,-0.84352 36.54359,-7.04816 52.53635,-11.11486 -36.10936,19.30926 -63.26858,28.34777 -52.53635,11.11486 z"
                                    class="JM-logo__m-tail" />
                                </g>
                                <path
                                    style="fill:none;stroke:#ffffff;stroke-width:2.71801;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:normal"
                                    d="M 10.124659,3.2366662 C 7.2729045,5.5425664 4.9996936,7.9702664 1.3588815,10.110051 c 2.901212,1.863808 3.9699434,2.916214 6.5220952,4.998185 0.3055824,0.249287 1.8691856,1.502017 2.0815626,2.143992"
                                    class="JM-logo__lt" />
                                <path
                                    style="display:inline;fill:none;stroke:#ffffff;stroke-width:2.71801;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:normal"
                                    d="m 57.938471,3.2348672 c 3.125037,1.9370882 5.222618,4.7827552 8.43598,6.8733838 -3.213326,2.061802 -5.63678,4.574962 -8.43598,6.87341"
                                    class="JM-logo__gt" />
                            </svg>
                            <!-- Tokenize SVG for optimization -->
                        </a>
                    </h1>
                    <a href="https://github.com/sharkleadership" class="nav__github"><img src="./assets/images/icons/github-mark-white.svg" alt="Link to sharkleadership's GitHub profile."></a>
                </nav>
            </header>
            <main id="top">
                <section id="hero" class="hero full-bleed">
                    <div class="three-d-face">
                        <div id="egghead-headset" class="p5-sketch-container"></div> <!-- end #egghead-headset.p5-sketch-container -->
                        <noscript>
                            <picture>
                                <source srcset="./assets/images/egghead.webp" type="image/webp">
                                <source srcset="./assets/images/egghead.png" type="image/png">
                                <img src="./assets/images/egghead.png" alt='An image of a 3D Model of an egghead wearing a VR headset.'>
                            </picture>
                        </noscript>
                    </div> <!-- end .three-d-face -->
                    <h2>
                        <span class="salutation__window">
                            <span class="salutation__glass">
                                <span>Hello!</span>
                                <span class="japanese" lang="ja">
                                    <span class="morning">おはようございます！</span>
                                    <span class="afternoon">こんにちは！</span>
                                    <span class="evening">こんばんは！</span>
                                </span>
                                <noscript><span class="japanese" lang="ja">こんにちは！</span></noscript>
                                <span lang="tok">toki a!</span>
                                <span>Hello!</span>
                            </span>
                        </span>
                        Jeremy Meyers · 
                        <br>
                        Web Developer
                    </h2>
                </section>
                <section id="about" class="about">
                    <h2 class="icon-before--about">About</h2>
                    <p>
                        Hello, I am <span class="JM-logo"><svg
                                viewBox="0 0 67.733329 20.720588"
                                version="1.1"
                                xmlns="http://www.w3.org/2000/svg"
                                role="img"
                                aria-label="<JM>">
                                <g
                                    class="JM-logo__jm-group"
                                    transform="matrix(0.09170011,0,0,0.09170313,-1.3426703,-13.027762)">
                                    <path
                                    style="fill:none;stroke:#1e90ff;stroke-width:32.6422;stroke-linecap:round;stroke-linejoin:round;stroke-opacity:1;paint-order:normal"
                                    d="m 294.51542,198.82659 c 57.75099,60.67636 -11.33134,218.87243 -108.91981,114.48594"
                                    class="JM-logo__j" />
                                    <path
                                    style="display:inline;fill:none;stroke:#1e90ff;stroke-width:32.6111;stroke-linecap:round;stroke-linejoin:round;stroke-opacity:1;paint-order:fill markers stroke"
                                    d="m 170.47387,211.35078 c 63.60558,-4.66523 147.90373,-43.13442 222.04248,-52.86148 17.67003,-1.51476 19.84077,8.54425 20.47473,14.64474 -2.33237,15.89532 -45.81636,65.29105 -55.78886,86.49105 25.7831,-15.05333 79.32976,-72.26693 121.58501,-72.92684 0.28109,14.2211 -14.3575,58.96575 5.04779,62.14087 48.7994,-3.72238 118.17024,-24.07337 111.74624,9.77557 l -21.97146,93.09728"
                                    class="JM-logo__m" />
                                    <path
                                    style="fill:none;fill-rule:evenodd;stroke:#1e90ff;stroke-width:6.47914;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;paint-order:fill markers stroke"
                                    d="m 570.09582,355.59595 c 29.53052,-0.84352 36.54359,-7.04816 52.53635,-11.11486 -36.10936,19.30926 -63.26858,28.34777 -52.53635,11.11486 z"
                                    class="JM-logo__m-tail" />
                                </g>
                                <path
                                    style="fill:none;stroke:#ffffff;stroke-width:2.71801;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:normal"
                                    d="M 10.124659,3.2366662 C 7.2729045,5.5425664 4.9996936,7.9702664 1.3588815,10.110051 c 2.901212,1.863808 3.9699434,2.916214 6.5220952,4.998185 0.3055824,0.249287 1.8691856,1.502017 2.0815626,2.143992"
                                    class="JM-logo__lt" />
                                <path
                                    style="display:inline;fill:none;stroke:#ffffff;stroke-width:2.71801;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:normal"
                                    d="m 57.938471,3.2348672 c 3.125037,1.9370882 5.222618,4.7827552 8.43598,6.8733838 -3.213326,2.061802 -5.63678,4.574962 -8.43598,6.87341"
                                    class="JM-logo__gt" />
                            </svg></span>. 
                        I started programming at a young age, inspired in part by Flash (may it rest in peace) and HTML5 games.
                    </p>
                    <div class="media">
                        <div class="rubiks-cube-container">
                            <div id="rubiks-cube" class="p5-sketch-container" title="Photo of a Rubik's Cube spinning."></div> <!-- end #rubiks-cube.p5-sketch-container -->
                            <noscript>
                                <picture>
                                    <source srcset="./assets/images/rubiks-turn.webp" type="image/webp">
                                    <source srcset="./assets/images/rubiks-turn.gif" type="image/gif">
                                    <img src="./assets/images/rubiks-turn.gif" alt="Photo of a Rubik's Cube spinning.">
                                </picture>
                            </noscript>
                        </div> <!-- end .rubiks-cube-container -->
                        <p>
                            As I grew, I continued learning and creating projects using basic HTML and JavaScript. Fast forward to the present, I am a student of Web Development & Digital Media!
                        </p>
                    </div> <!-- end .media -->
                    <p>
                        In junction with programming languages, I also enjoy learning about human languages and linguistics and solving puzzles such as Rubik's Cubes.
                    </p>
                </section>
                <section id="projects" class="projects">
                    <h2 class="icon-before--projects">Projects</h2>
                    <ul>
                        <li>
                            <figure class="projects__card">
                                <button class="projects__button" popovertarget="languagebox-desc">
                                        <picture>
                                            <source srcset="./assets/images/projects/languagebox/languagebox_DesktopXL_01.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Desktop_01.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Tablet_01.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Mobile_01.png" media="(width < 768px)" height="1592" width="896">
                                            <img class="projects__picture" src="./assets/images/projects/languagebox/languagebox_Mobile_01.png" alt="Example of the website LanguageBox." height="1592" width="896">
                                        </picture>
                                    <span class="projects__button__title">LanguageBox</span>
                                </button>
                                <dialog class="projects__desc languagebox-desc" id="languagebox-desc" popover>
                                    <button popovertarget="languagebox-desc" popovertargetaction="hide" class="projects__desc__hide" aria-label="Close">
                                        <span class="bar bar--left"></span>
                                        <span class="bar bar--right"></span>
                                    </button>
                                    <div class="picture-container">
                                        <picture>
                                            <source srcset="./assets/images/projects/languagebox/languagebox_DesktopXL_01.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Desktop_01.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Tablet_01.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Mobile_01.png" media="(width < 768px)" height="1592" width="896">
                                            <img class="projects__picture" src="./assets/images/projects/languagebox/languagebox_Mobile_01.png" alt="Example of the website LanguageBox." height="1592" width="896">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/languagebox/languagebox_DesktopXL_02.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Desktop_02.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Tablet_02.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Mobile_02.png" media="(width < 768px)" height="1592" width="896">
                                            <img class="projects__picture" src="./assets/images/projects/languagebox/languagebox_Mobile_01.png" alt="Example of the website LanguageBox." height="1592" width="896">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/languagebox/languagebox_DesktopXL_03.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Desktop_03.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Tablet_03.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Mobile_03.png" media="(width < 768px)" height="1592" width="896">
                                            <img class="projects__picture" src="./assets/images/projects/languagebox/languagebox_Mobile_01.png" alt="Example of the website LanguageBox." height="1592" width="896">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Tablet_04.png" media="(width >= 768px)" height="696" width="1536">
                                            <source srcset="./assets/images/projects/languagebox/languagebox_Mobile_04.png" media="(width < 768px)" height="804" width="896">
                                            <img class="projects__picture" src="./assets/images/projects/languagebox/languagebox_Mobile_01.png" alt="Example of the website LanguageBox.">
                                        </picture>
                                    </div> <!-- end .picture-container -->
                                    <div class="info">
                                        <hgroup>
                                            <img src="./assets/images/projects/languagebox-logo.png" alt="LanguageBox logo." class="project-logo--languagebox">
                                            <h3>LanguageBox</h3>
                                        </hgroup>
                                        <div class="links">
                                            <a href="https://github.com/sharkleadership/languagebox" title="Link to LanguageBox's GitHub repo."><img src="./assets/images/icons/github-mark-white.svg" alt="GitHub logo." class="nav__github"></a>
                                            <a href="./languagebox" title="Link to LanguageBox"><img src="./assets/images/icons/material--open-link.svg" alt="Open link."></a>
                                        </div> <!-- end .links -->
                                        <p>
                                            LanguageBox is a blog/learning resource dedicated to toki pona, the language of good. toki pona is a constructed language created in 2001 by Sonja Lang, known within the community as jan Sonja.
                                        </p>
                                        <p>
                                            This project's goal was to create a WordPress website on a topic of my choosing. The website needed to offer at least four pages and four posts of content.
                                        </p>
                                        <p>
                                            This project was created as my final project in WEB 172 - WordPress I.
                                        </p>
                                    </div> <!-- end .info -->
                                </dialog>
                            </figure>
                        </li>
                        <li>
                            <figure class="projects__card">
                                <button class="projects__button" popovertarget="JMdev-desc">
                                    <div id="JMdev-droste-container" class="projects__picture--droste">
                                        <div class="disable-iframe-clicks"></div> <!-- end .disable-iframe-clicks -->
                                    </div> <!-- end #JM-droste-container.projects__picture--droste -->
                                    <noscript>
                                        <picture>
                                            <source srcset="./assets/images/projects/droste/jmdev-DesktopXL_01.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/droste/jmdev-Desktop_01.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/droste/jmdev-Tablet_01.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/droste/jmdev-Mobile_01.png" media="(width < 768px)" height="1333" width="750">
                                            <img class="projects__picture" src="./assets/images/projects/droste/jmdev-Mobile_01.png" alt="Example of the website jeremymeyers.dev." height="1333" width="750">
                                        </picture>
                                    </noscript>
                                    <span class="projects__button__title">jeremymeyers<wbr>.dev</span>
                                </button>
                                <dialog class="projects__desc JMdev-desc" id="JMdev-desc" popover>
                                    <button popovertarget="JMdev-desc" popovertargetaction="hide" class="projects__desc__hide" aria-label="Close">
                                        <span class="bar bar--left"></span>
                                        <span class="bar bar--right"></span>
                                    </button>
                                    <div class="picture-container">
                                        <picture>
                                            <source srcset="./assets/images/projects/droste/jmdev-DesktopXL_01.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/droste/jmdev-Desktop_01.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/droste/jmdev-Tablet_01.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/droste/jmdev-Mobile_01.png" media="(width < 768px)" height="1333" width="750">
                                            <img class="projects__picture" src="./assets/images/projects/droste/jmdev-Mobile_01.png" alt="Example of the website jeremymeyers.dev." height="1333" width="750">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/droste/jmdev-DesktopXL_02.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/droste/jmdev-Desktop_02.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/droste/jmdev-Tablet_02.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/droste/jmdev-Mobile_02.png" media="(width < 768px)" height="1333" width="750">
                                            <img class="projects__picture" src="./assets/images/projects/droste/jmdev-Mobile_02.png" alt="Example of the website jeremymeyers.dev." height="1333" width="750">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/droste/jmdev-DesktopXL_03.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/droste/jmdev-Desktop_03.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/droste/jmdev-Tablet_03.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/droste/jmdev-Mobile_03.png" media="(width < 768px)" height="1333" width="750">
                                            <img class="projects__picture" src="./assets/images/projects/droste/jmdev-Mobile_03.png" alt="Example of the website jeremymeyers.dev." height="1333" width="750">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/droste/jmdev-DesktopXL_04.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/droste/jmdev-Desktop_04.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/droste/jmdev-Tablet_04.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/droste/jmdev-Mobile_04.png" media="(width < 768px)" height="1333" width="750">
                                            <img class="projects__picture" src="./assets/images/projects/droste/jmdev-Mobile_04.png" alt="Example of the website jeremymeyers.dev." height="1333" width="750">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/droste/jmdev-DesktopXL_05.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/droste/jmdev-Desktop_05.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/droste/jmdev-Tablet_05.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/droste/jmdev-Mobile_05.png" media="(width < 768px)" height="1333" width="750">
                                            <img class="projects__picture" src="./assets/images/projects/droste/jmdev-Mobile_05.png" alt="Example of the website jeremymeyers.dev." height="1333" width="750">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/droste/jmdev-DesktopXL_06.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/droste/jmdev-Desktop_06.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/droste/jmdev-Tablet_06.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/droste/jmdev-Mobile_06.png" media="(width < 768px)" height="1333" width="750">
                                            <img class="projects__picture" src="./assets/images/projects/droste/jmdev-Mobile_06.png" alt="Example of the website jeremymeyers.dev." height="1333" width="750">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/droste/jmdev-DesktopXL_07.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/droste/jmdev-Desktop_07.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/droste/jmdev-Tablet_07.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/droste/jmdev-Mobile_07.png" media="(width < 768px)" height="1333" width="750">
                                            <img class="projects__picture" src="./assets/images/projects/droste/jmdev-Mobile_07.png" alt="Example of the website jeremymeyers.dev." height="1333" width="750">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/droste/jmdev-Desktop_08.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/droste/jmdev-Mobile_08.png" media="(width < 768px)" height="1333" width="750">
                                            <img class="projects__picture" src="./assets/images/projects/droste/jmdev-Mobile_08.png" alt="Example of the website jeremymeyers.dev." height="1333" width="750">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/droste/jmdev-Desktop_09.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/droste/jmdev-Mobile_09.png" media="(width < 768px)" height="1333" width="750">
                                            <img class="projects__picture" src="./assets/images/projects/droste/jmdev-Mobile_09.png" alt="Example of the website jeremymeyers.dev." height="1333" width="750">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/droste/jmdev-Desktop_10.png" media="(width >= 1024px) and (width < 1440px)" height="1152" width="2048">
                                            <img class="projects__picture" src="./assets/images/projects/droste/jmdev-Mobile_01.png" alt="Example of the website jeremymeyers.dev." height="1333" width="750">
                                        </picture>
                                    </div> <!-- end .picture-container -->
                                    <div class="info">
                                        <hgroup>
                                            <img src="./assets/images/JM-tag-logo-dark.svg" alt="<JM> logo." class="project-logo--JM">
                                            <h3>jeremymeyers<wbr>.dev</h3>
                                        </hgroup>
                                        <div class="links">
                                            <a href="https://github.com/sharkleadership/WEB290" title="Link to jeremymeyers.dev's GitHub repo."><img src="./assets/images/icons/github-mark-white.svg" alt="GitHub logo." class="nav__github"></a>
                                            <a href="<?=$_SERVER['PHP_SELF']?>" title="Link to jeremymeyers.dev"><img src="./assets/images/icons/material--open-link.svg" alt="Open link."></a>
                                        </div> <!-- end .links -->
                                        <p>
                                            jeremymeyers.dev, affectionately called &lpar;by me&rpar; JMDev, is my portfolio, and coincidentally, the website you are on currently!
                                        </p>
                                        <p>
                                            Originally hobbled together over the course of my scholarship, JMDev has now been redesigned as a semester-long project for my capstone class &lpar;WEB 290&rpar;.
                                        </p>
                                    </div> <!-- end .info -->
                                </dialog>
                            </figure>
                        </li>
                        <li>
                            <figure class="projects__card">
                                <button class="projects__button" popovertarget="kcdr-desc">
                                        <picture>
                                            <source srcset="./assets/images/projects/KCDR/KCDR_DesktopXL_01.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Desktop_01.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Tablet_01.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Mobile_01.png" media="(width < 768px)" height="1511" width="850">
                                            <img class="projects__picture" src="./assets/images/projects/KCDR/KCDR_Mobile_01.png" alt="Example of the website KCDR." height="1511" width="850">
                                        </picture>
                                    <span class="projects__button__title">Kansas City Doberman Rescue</span>
                                </button>
                                <dialog class="projects__desc kcdr-desc" id="kcdr-desc" popover>
                                    <button popovertarget="kcdr-desc" popovertargetaction="hide" class="projects__desc__hide" aria-label="Close">
                                        <span class="bar bar--left"></span>
                                        <span class="bar bar--right"></span>
                                    </button>
                                    <div class="picture-container">
                                        <picture>
                                            <source srcset="./assets/images/projects/KCDR/KCDR_DesktopXL_01.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Desktop_01.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Tablet_01.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Mobile_01.png" media="(width < 768px)" height="1511" width="850">
                                            <img class="projects__picture" src="./assets/images/projects/KCDR/KCDR_Mobile_01.png" alt="Example of the website KCDR." height="1511" width="850">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/KCDR/KCDR_DesktopXL_02.png" media="(width >= 1440px)" height="1620" width="2880">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Desktop_02.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Tablet_02.png" media="(width >= 768px)" height="2048" width="1536">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Mobile_02.png" media="(width < 768px)" height="1511" width="850">
                                            <img class="projects__picture" src="./assets/images/projects/KCDR/KCDR_Mobile_01.png" alt="Example of the website KCDR." height="1511" width="850">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/KCDR/KCDR_DesktopXL_03.png" media="(width >= 1440px)" height="1344" width="2880">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Desktop_03.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Tablet_03.png" media="(width >= 768px)" height="1342" width="1536">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Mobile_03.png" media="(width < 768px)" height="1511" width="850">
                                            <img class="projects__picture" src="./assets/images/projects/KCDR/KCDR_Mobile_01.png" alt="Example of the website KCDR." height="1592" width="850">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Desktop_04.png" media="(width >= 1024px)" height="1152" width="2048">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Mobile_04.png" media="(width < 768px)" height="1511" width="850">
                                            <img class="projects__picture" src="./assets/images/projects/KCDR/KCDR_Mobile_01.png" alt="Example of the website KCDR.">
                                        </picture>
                                        <picture>
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Desktop_05.png" media="(width >= 1024px)" height="158" width="2048">
                                            <source srcset="./assets/images/projects/KCDR/KCDR_Mobile_05.png" media="(width < 768px)" height="1286" width="850">
                                            <img class="projects__picture" src="./assets/images/projects/KCDR/KCDR_Mobile_01.png" alt="Example of the website KCDR.">
                                        </picture>
                                    </div> <!-- end .picture-container -->
                                    <div class="info">
                                        <hgroup>
                                            <img src="./assets/images/projects/KCDR-logo.svg" alt="KCDR (redesigned) logo." class="project-logo--KCDR">
                                            <h3>Kansas City Doberman Rescue</h3>
                                        </hgroup>
                                        <div class="links">
                                            <a href="https://github.com/sharkleadership/WEB122/tree/main/KCDR" title="Link to KCDR's GitHub repo."><img src="./assets/images/icons/github-mark-white.svg" alt="GitHub logo." class="nav__github"></a>
                                            <a href="./kcdr" title="Link to KCDR"><img src="./assets/images/icons/material--open-link.svg" alt="Open link."></a>
                                        </div> <!-- end .links -->
                                        <p>
                                            Kansas City Doberman Rescue is a volunteer organization that rescues Dobermans.
                                        </p>
                                        <p>
                                            This project's goal was to redesign their website to fix some of its challenges, focusing on its disjointed styles, outdated code, and content fatigue.
                                        </p>
                                        <p>
                                            This project was created as my final project in WEB 122 – CSS Techniques and Projects.
                                        </p>
                                    </div> <!-- end .info -->
                                </dialog>
                            </figure>
                        </li>
                    </ul>
                    <section class="other-projects">
                        <h3>Other Projects</h3>
                        <ul>
                            <li class="other-projects__proj media">
                                <img src="./assets/images/projects/css-paged-media-logo.png" alt="CSS Paged Media logo." class="other-project-logo--CSSPM">
                                <details>
                                    <summary>
                                        <h4>CSS Paged Media</h4>
                                        <span>CSS Paged Media is a one-page website that details the CSS Paged Media module.</span>
                                    </summary>
                                    <div class="links">
                                        <a href="https://github.com/sharkleadership/WEB122/tree/main/css-paged-media" title="Link to CSS Paged Media's GitHub repo."><img src="./assets/images/icons/github-mark-white.svg" alt="GitHub logo." class="nav__github"></a>
                                        <a href="./css-paged-media" title="Link to CSS Paged Media"><img src="./assets/images/icons/material--open-link.svg" alt="Open link."></a>
                                    </div> <!-- end .links -->
                                    <p>
                                        CSS Paged Media is a one-page website that details the CSS Paged Media module.
                                    </p>
                                    <p>
                                        Other related features such as page counters and fragmentation are also discussed.
                                    </p>
                                    <p>
                                        This project was created as my midterm project in WEB 122 – CSS Techniques and Projects.
                                    </p>
                                </details>
                            </li>
                            <li class="other-projects__proj media">
                                <img src="./assets/images/projects/RPSLS-logo.svg" alt="Rock, Paper, Scissors, Lizard, Spock logo." class="other-project-logo--RPSLS">
                                <details>
                                    <summary>
                                        <h4>RPSLS</h4>
                                        <span>RPSLS is an extension of the popular game Rock, Paper, Scissors written in JavaScript.</span>
                                    </summary>
                                    <div class="links">
                                        <a href="https://github.com/sharkleadership/sharkleadership.github.io/tree/main/WEB114/final" title="Link to RPSLS's GitHub repo."><img src="./assets/images/icons/github-mark-white.svg" alt="GitHub logo." class="nav__github"></a>
                                        <a href="./rpsls" title="Link to RPSLS"><img src="./assets/images/icons/material--open-link.svg" alt="Open link."></a>
                                    </div> <!-- end .links -->
                                    <p>
                                        RPSLS is an extension of the popular game Rock, Paper, Scissors written in JavaScript.
                                    </p>
                                    <p>
                                        This project was created as my final project for WEB 114 – JavaScript I.
                                    </p>
                                </details>
                            </li>
                            <li class="other-projects__proj media">
                                <img src="./assets/images/projects/Portal-The-Movie-logo.png" alt="Portal: The Movie unofficial logo." class="other-project-logo--portal">
                                <details>
                                    <summary>
                                        <h4>Portal: The Movie</h4>
                                        <span>Portal: The Movie is a high-fidelity Figma prototype of a promotional website for a &lpar;currently&rpar; fictional movie.</span>
                                    </summary>
                                    <div class="links">
                                        <a href="https://www.figma.com/design/jDsscSunf6mLJ7C27mykco/HFP_F_Portal" title="Link to Portal: The Movie's Figma file."><img src="./assets/images/icons/logos--figma.svg" alt="Figma logo." class="nav__figma"></a>
                                        <a href="https://www.figma.com/proto/jDsscSunf6mLJ7C27mykco/HFP_F_Portal" title="Link to Portal: The Movie as a prototype."><img src="./assets/images/icons/material--open-link.svg" alt="Open link."></a>
                                    </div> <!-- end .links -->
                                    <p>
                                        Portal: The Movie is a high-fidelity Figma prototype of a promotional website for a &lpar;currently&rpar; fictional movie.
                                    </p>
                                    <p>
                                        This project was created as my final project for WEB 126 – Technical Interface Skills.
                                    </p>
                                </details>
                            </li>
                        </ul>
                    </section>
                </section>
                <section id="contact" class="contact">
                    <h2 class="icon-before--contact">Contact</h2>
                    <p>
                        Want to get in touch? Here are some methods of contact:
                    </p>
                    <ul class="contact-method-list">
                        <li><a href="mailto:jeremy@jeremymeyers.dev" class="icon-before--email--dark contact-button contact-button--email">Email</a></li>
                        <li><button disabled="disabled" class="icon-before--video-response--dark">YouTube video response</button><a href="#contact__foot--1"><sup>1</sup></a></li>
                        <li><button disabled="disabled" class="icon-before--pigeon--dark">Carrier pigeon</button><a href="#contact__foot--2"><sup>2</sup></a></li>
                    </ul>
                    <footer>
                        <p id="contact__foot--1"><sup>1:</sup> This feature no longer exists? As far as I'm aware, it's been discontinued for at least a decade.</p>
                        <p id="contact__foot--2"><sup>2:</sup> On principle, I will not communicate through carrier pigeons. They know what they did.</p>
                    </footer>
                </section>
                <section id="vote" class="vote">
                    <h2 class="icon-before--vote">Vote</h2>
                    <p>Voting is a very important act. Whether it is for public office, or even just what to get for dinner tonight, having your voice heard is crucial.</p>
                    
                    <h3 class="icon-before--question">Question</h3>
                    <p class="question-box">Dogs or cats?</p>

                    <?php if (empty($_POST['choice'])) { ?>
                        <form action="<?=$_SERVER['PHP_SELF']?>#vote" method="POST">

                            <div class="choices">
                                <label tabindex="0">
                                    <input type="radio" id="choice--1" class="voting-radio" name="choice" value="1" required>
                                    🐶 Dogs
                                </label>
                                <label tabindex="0">
                                    <input type="radio" id="choice--2" class="voting-radio" name="choice" value="2">
                                    🐱 Cats
                                </label>
                                <label tabindex="0" hidden>
                                    <input type="radio" id="choice--3" class="voting-radio" name="choice" value="3">
                                </label>
                                <label tabindex="0" hidden>
                                    <input type="radio" id="choice--4" class="voting-radio" name="choice" value="4">
                                </label>
                            </div> <!-- end .choices -->

                            <input type="submit" class="submit" value="Submit">
                        </form>
                        <footer>
                            <p>Privacy is crucial in all aspects of life, including voting. Your vote is completely confidential.</p>
                        </footer>
                    <?php } else { ?>
                        <p>Thank you for participating! Your vote has been cast.</p>
                        <figure class="vote-graph">
                            <div class="vote-graph__graph">
                                <div class="plurality" data-majority="<?=round((($tally_max / $tally_sum) * 100), 2)?>%" style="--plurality: <?=round((100 - ($tally_max / $tally_sum) * 100), 2)?>%;"></div> <!-- end .plurality -->
                                <div class="plurality" data-majority="<?=round((100 / $num_choices), 2)?>%" style="--plurality: <?=round(100 - (100 / $num_choices), 2)?>%;"></div> <!-- end .plurality -->
                                <?php
                                    for ($i = 0; $i < $num_choices; $i++) {
                                        $tally = $tallies[$i];
                                ?>
                                    <div class="vote-graph__bar<?= ($tally['tally'] == $tally_max) ? ' winning' : '' ?><?= ($i + 1 == $user_choice) ? ' user-choice' :  '' ?>"
                                    style="--tally: <?=(($tally['tally'] / $tally_sum) * 100)?>%;"
                                    data-choice="<?=$tally['choice']?>"
                                    data-tally="<?=$tally['tally']?>">
                                    </div> <!-- end .vote-graph__bar -->
                                <?php } ?>
                                
                            </div>
                            <figcaption class="vote-graph__legend">
                                <ul>
                                    <li><div class="vote-graph__legend__color winning"></div> <!-- end .vote-graph__legend__color --> - Leading</li>
                                    <li><div class="vote-graph__legend__color user-choice"></div> <!-- end .vote-graph__legend__color --> - Your Choice</li>
                                </ul>
                            </figcaption>
                        </figure>
                    <?php } ?>
                </section>
            </main>
            <footer class="footer full-bleed">
                <div class="JM-logo">
                    <svg
                        viewBox="0 0 67.733329 20.720588"
                        version="1.1"
                        xmlns="http://www.w3.org/2000/svg"
                        role="img"
                        aria-label="<JM> logo.">
                        <g
                            class="JM-logo__jm-group"
                            transform="matrix(0.09170011,0,0,0.09170313,-1.3426703,-13.027762)">
                            <path
                            style="fill:none;stroke:#1e90ff;stroke-width:32.6422;stroke-linecap:round;stroke-linejoin:round;stroke-opacity:1;paint-order:normal"
                            d="m 294.51542,198.82659 c 57.75099,60.67636 -11.33134,218.87243 -108.91981,114.48594"
                            class="JM-logo__j" />
                            <path
                            style="display:inline;fill:none;stroke:#1e90ff;stroke-width:32.6111;stroke-linecap:round;stroke-linejoin:round;stroke-opacity:1;paint-order:fill markers stroke"
                            d="m 170.47387,211.35078 c 63.60558,-4.66523 147.90373,-43.13442 222.04248,-52.86148 17.67003,-1.51476 19.84077,8.54425 20.47473,14.64474 -2.33237,15.89532 -45.81636,65.29105 -55.78886,86.49105 25.7831,-15.05333 79.32976,-72.26693 121.58501,-72.92684 0.28109,14.2211 -14.3575,58.96575 5.04779,62.14087 48.7994,-3.72238 118.17024,-24.07337 111.74624,9.77557 l -21.97146,93.09728"
                            class="JM-logo__m" />
                            <path
                            style="fill:none;fill-rule:evenodd;stroke:#1e90ff;stroke-width:6.47914;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;paint-order:fill markers stroke"
                            d="m 570.09582,355.59595 c 29.53052,-0.84352 36.54359,-7.04816 52.53635,-11.11486 -36.10936,19.30926 -63.26858,28.34777 -52.53635,11.11486 z"
                            class="JM-logo__m-tail" />
                        </g>
                        <path
                            style="fill:none;stroke:#ffffff;stroke-width:2.71801;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:normal"
                            d="M 10.124659,3.2366662 C 7.2729045,5.5425664 4.9996936,7.9702664 1.3588815,10.110051 c 2.901212,1.863808 3.9699434,2.916214 6.5220952,4.998185 0.3055824,0.249287 1.8691856,1.502017 2.0815626,2.143992"
                            class="JM-logo__lt" />
                        <path
                            style="display:inline;fill:none;stroke:#ffffff;stroke-width:2.71801;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:normal"
                            d="m 57.938471,3.2348672 c 3.125037,1.9370882 5.222618,4.7827552 8.43598,6.8733838 -3.213326,2.061802 -5.63678,4.574962 -8.43598,6.87341"
                            class="JM-logo__gt" />
                    </svg>
                </div> <!-- end .JM-logo -->
                <nav>
                    <a href="#top" class="back-to-top">Back to Top</a>
                </nav>
            </footer>
        </div> <!-- end .wrapper -->
    </body>
</html>