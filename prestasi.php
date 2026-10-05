<?php

require_once __DIR__ . '/includes/database.php';
$achievements = database()->query('SELECT * FROM achievements ORDER BY sort_order, id')->fetchAll();
$escape = static fn (?string $value): string => htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
$pageTitle = 'Prestasi | Devandria Losias';

include "includes/header.php";
include "includes/sidebar.php";

?>

<main class="content">

    <section class="page-header">

        <h1>Pencapaian</h1>

        <p>
            Pencapaian yang merepresentasikan proses belajar,
            pengembangan kompetensi, dan komitmen saya terhadap
            peningkatan profesional secara berkelanjutan.
        </p>

    </section>


    <div class="section-divider"></div>


    <p class="total">
        Total: <?php echo count($achievements); ?>
    </p>


    <!-- Achievement -->
    <div class="achievement-grid">
        <?php foreach ($achievements as $achievement): ?>
        <article class="achievement-card">

            <img src="<?php echo $escape($achievement['image_path']); ?>" alt="<?php echo $escape($achievement['title']); ?>">

            <div class="achievement-body">

                <?php if ($achievement['certificate_code'] !== ''): ?><small><?php echo $escape($achievement['certificate_code']); ?></small><?php endif; ?>

                <h3>
                    <?php echo $escape($achievement['title']); ?>
                </h3>

                <p>
                    <?php echo $escape($achievement['issuer']); ?>
                </p>

                <?php if (trim($achievement['tags']) !== ''): ?>
                <div class="tags">
                    <?php foreach (explode(',', $achievement['tags']) as $tag): ?><span><?php echo $escape(trim($tag)); ?></span><?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($achievement['published_label'] !== ''): ?>
                <div class="card-date"><?php echo $escape($achievement['published_label']); ?></div>
                <?php endif; ?>

            </div>

        </article>
        <?php endforeach; ?>

    </div>

</main>

<?php include "includes/footer.php"; ?>