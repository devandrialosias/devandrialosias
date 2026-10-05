<?php

require_once __DIR__ . '/includes/database.php';
$projects = database()->query('SELECT * FROM projects ORDER BY sort_order, id')->fetchAll();
$escape = static fn (?string $value): string => htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
$pageTitle = 'Proyek | Devandria Losias';

include "includes/header.php";
include "includes/sidebar.php";

?>

<main class="content">

    <section class="page-header">

        <h1>Proyek</h1>

        <p>
            Kumpulan proyek yang saya bangun untuk menyelesaikan
            masalah nyata, meningkatkan efisiensi, dan menghadirkan
            solusi digital yang scalable.
        </p>

    </section>


    <div class="section-divider"></div>


    <div class="project-grid">
        <?php foreach ($projects as $project): ?>
        <article class="project-card">
            <img src="<?php echo $escape($project['image_path']); ?>" alt="<?php echo $escape($project['title']); ?>">
            <div class="project-content">
                <h2><?php echo $escape($project['title']); ?></h2>
                <p><?php echo nl2br($escape($project['description'])); ?></p>
                <?php if (trim($project['technologies']) !== ''): ?>
                    <div class="technology">
                        <?php foreach (explode(',', $project['technologies']) as $technology): ?>
                            <span><?php echo $escape(trim($technology)); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

</main>

<?php include "includes/footer.php"; ?>