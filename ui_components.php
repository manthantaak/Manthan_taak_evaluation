<?php
require_once __DIR__ . '/db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = 'UI Components';
require __DIR__ . '/header.php';
?>
<div class="page-heading">
    <p class="section-label mb-2">Bootstrap 5</p>
    <h1 class="h2">UI components</h1>
    <p class="mb-0">A few useful components styled for the photography portfolio.</p>
</div>

<div class="alert alert-success" role="alert">
    Your portfolio is ready for new photographs. Add an entry whenever you like.
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <section class="card content-card">
            <div class="card-body p-4">
                <h2 class="h4 mb-3">Frequently asked questions</h2>
                <div class="accordion" id="photoAccordion">
                    <div class="accordion-item">
                        <h3 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#answerOne" aria-expanded="true" aria-controls="answerOne">How do I add a photograph?</button></h3>
                        <div id="answerOne" class="accordion-collapse collapse show" data-bs-parent="#photoAccordion"><div class="accordion-body">Choose Add Photograph in the navigation, fill in the details, and select a JPG, PNG, or WEBP image.</div></div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answerTwo" aria-expanded="false" aria-controls="answerTwo">Can I change an image later?</button></h3>
                        <div id="answerTwo" class="accordion-collapse collapse" data-bs-parent="#photoAccordion"><div class="accordion-body">Yes. Open Manage Photographs, choose Edit, and select a replacement image. Leave the image field empty to keep the current one.</div></div>
                    </div>
                    <div class="accordion-item">
                        <h3 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#answerThree" aria-expanded="false" aria-controls="answerThree">How do I download the list?</button></h3>
                        <div id="answerThree" class="accordion-collapse collapse" data-bs-parent="#photoAccordion"><div class="accordion-body">Use Download Excel (CSV) to open the list in spreadsheet software, or Download PDF for a printable copy.</div></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-lg-5">
        <article class="card photo-card">
            <img src="https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&amp;fit=crop&amp;w=1000&amp;q=80" class="card-img-top" alt="Mountain lake at sunset">
            <div class="card-body">
                <p class="photo-category mb-2">Sample card</p>
                <h2 class="h5">A quiet evening</h2>
                <p class="text-muted-custom mb-0">A sample Bootstrap photography card. Add your own photographs to show them in the gallery.</p>
            </div>
        </article>
    </div>
</div>
<?php require __DIR__ . '/footer.php'; ?>
