<?php

require_once __DIR__ . '/includes/database.php';
$contacts = database()->query('SELECT * FROM contact_links ORDER BY sort_order, id')->fetchAll();
$escape = static fn (?string $value): string => htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
$pageTitle = 'Kontak | Devandria Losias';

include "includes/header.php";
include "includes/sidebar.php";

?>

<main class="content">

    <section class="page-header">

        <h1>Kontak</h1>

        <p>
            Terbuka untuk kolaborasi, diskusi teknologi,
            dan peluang profesional.
        </p>

    </section>


    <div class="section-divider"></div>


    <p class="social-title">
        TEMUKAN SAYA DI MEDIA SOSIAL
    </p>


    <div class="contact-grid">
        <?php foreach ($contacts as $contact): ?>
        <div class="contact-card <?php echo $escape($contact['card_class']); ?>">
            <div>
                <h2><?php echo $escape($contact['title']); ?></h2>
                <p><?php echo nl2br($escape($contact['description'])); ?></p>
                <a href="<?php echo $escape($contact['url']); ?>" class="contact-button" <?php echo str_starts_with($contact['url'], 'http') ? 'target="_blank"' : ''; ?>>
                    <?php echo $contact['platform'] === 'whatsapp' ? 'Chat WhatsApp' : 'Lihat ' . $escape(ucfirst($contact['platform'])); ?>
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            </div>
            <div class="contact-icon"><i class="<?php echo $escape($contact['icon_class']); ?>"></i></div>
        </div>
        <?php endforeach; ?>
    </div>


    <div class="section-divider"></div>


    <!-- Form -->
    <section class="message-section">

        <p class="social-title">
            ATAU KIRIM PESAN
        </p>


        <form
            action="proses_kontak.php"
            method="POST"
            class="contact-form"
        >

            <div class="form-row">

                <input
                    type="text"
                    name="nama"
                    placeholder="Nama"
                    required
                >

                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    required
                >

            </div>


            <input
                type="text"
                name="subjek"
                placeholder="Subjek"
                required
            >


            <textarea
                name="pesan"
                rows="7"
                placeholder="Tulis pesan..."
                required
            ></textarea>


            <button
                type="submit"
                class="submit-button"
            >
                Kirim Pesan
                <i class="fa-solid fa-paper-plane"></i>
            </button>

        </form>

    </section>

</main>

<?php include "includes/footer.php"; ?>