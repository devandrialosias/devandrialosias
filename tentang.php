<?php

require_once __DIR__ . '/includes/database.php';
$pdo = database();
$contentRows = $pdo->query('SELECT content_key, content_value FROM site_content')->fetchAll();
$content = [];
foreach ($contentRows as $row) $content[$row['content_key']] = $row['content_value'];
$career = $pdo->query('SELECT * FROM careers ORDER BY sort_order, id LIMIT 1')->fetch();
$education = $pdo->query('SELECT * FROM education ORDER BY sort_order, id')->fetchAll();
$escape = static fn (?string $value): string => htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
$pageTitle = 'Tentang | ' . ($content['profile_name'] ?? 'Devandria Losias');

include "includes/header.php";
include "includes/sidebar.php";

?>

<main class="content">

    <section class="page-header">

        <h1>Tentang</h1>

        <p>
            Pengenalan singkat mengenai siapa saya.
        </p>

    </section>


    <div class="section-divider"></div>


    <section class="about-text">

        <?php if (!empty($content['about_description'])): ?>
            <p><?php echo nl2br($escape($content['about_description'])); ?></p>
        <?php endif; ?>

        <p>
            Salam hangat,
        </p>


        <div class="signature">
            <?php echo $escape($content['about_signature'] ?? $content['profile_name'] ?? 'Devandria Losias'); ?>
        </div>

    </section>


    <div class="section-divider"></div>


    <!-- Pendidikan -->
    <section>

        <div class="section-heading">

            <h2>
                <i class="fa-solid fa-graduation-cap"></i>
                Pendidikan
            </h2>

            <p>
                Riwayat pendidikan saya.
            </p>

        </div>


        <?php foreach ($education as $item): ?>
        <div class="career-card education-card">

            <div class="career-logo">
                <img src="<?php echo $escape($item['logo_path'] ?: ($content['profile_photo'] ?? 'assets/img/profile.jpg')); ?>" alt="<?php echo $escape($item['institution']); ?>">
            </div>

            <div class="career-content">

                <h3><?php echo $escape($item['degree']); ?></h3>

                <p>
                    <?php echo $escape($item['institution']); ?>
                    <span>•</span>
                    <?php echo $escape($item['location']); ?>
                </p>

                <small><?php echo $escape($item['start_date'] . ' – ' . $item['end_date']); ?></small>

            </div>

        </div>
        <?php endforeach; ?>

    </section>


    <div class="section-divider"></div>


    <!-- Karier -->
    <section>

        <div class="section-heading">

            <h2>
                <i class="fa-solid fa-suitcase"></i>
                Karier
            </h2>

            <p>
                Perjalanan profesional saya.
            </p>

        </div>


        <?php if ($career): ?>
        <div class="career-card">

            <div class="career-logo">
                <img src="<?php echo $escape($career['logo_path'] ?: ($content['profile_photo'] ?? 'assets/img/profile.jpg')); ?>" alt="<?php echo $escape($career['company']); ?>">
            </div>

            <div class="career-content">

                <h3>
                    <?php echo $escape($career['title']); ?>
                </h3>

                <p>
                    <?php echo $escape($career['company']); ?>
                    <span>•</span>
                    <?php echo $escape($career['location']); ?>
                </p>

                <small>
                    <?php echo $escape($career['start_date'] . ' – ' . $career['end_date']); ?>
                </small>

                <span class="badge yellow">
                    <?php echo $escape($career['badge']); ?>
                </span>

            </div>

        </div>
        <?php endif; ?>

    </section>

</main>

<?php include "includes/footer.php"; ?>