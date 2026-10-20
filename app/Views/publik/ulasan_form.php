<section class="py-10 bg-light" id="ulasan-pelayanan" aria-labelledby="ulasan-title">
    <div class="container">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-6 p-lg-10">
                <h2 class="fw-bold text-primary mb-3" id="ulasan-title">Beri Rating dan Masukan</h2>
                <p class="text-gray-700 mb-6">Bagaimana pengalaman Anda menggunakan pelayanan website ini? Saran dan kritik Anda membantu kami meningkatkan pelayanan.</p>
                <form id="form-ulasan" action="<?= site_url('api/ulasan/save') ?>" method="post">
                    <?= csrf_field() ?>
                    <fieldset class="mb-6">
                        <legend class="fs-6 fw-bold">Rating pelayanan <span class="text-danger">*</span></legend>
                        <div class="d-flex flex-wrap gap-3" id="ulasan-stars">
                            <?php for ($bintang = 1; $bintang <= 5; $bintang++): ?>
                                <label class="ulasan-star-option text-center" title="<?= $bintang ?> bintang">
                                    <input type="radio" class="form-check-input" name="rating" value="<?= $bintang ?>" required aria-label="<?= $bintang ?> bintang" aria-describedby="ulasan-rating-error">
                                    <span class="d-block fs-1 text-gray-500 ulasan-star" aria-hidden="true">★</span>
                                    <span class="fs-7"><?= $bintang ?></span>
                                </label>
                            <?php endfor ?>
                        </div>
                        <div class="text-danger fs-7" id="ulasan-rating-error" data-ulasan-error="rating"></div>
                    </fieldset>
                    <div class="row g-5">
                        <div class="col-md-4">
                            <label class="form-label" for="ulasan-nama">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" id="ulasan-nama" autocomplete="name" minlength="2" maxlength="100" required aria-describedby="ulasan-nama-error">
                            <div class="text-danger fs-7" id="ulasan-nama-error" data-ulasan-error="nama"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="ulasan-no-hp">Nomor telepon <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="no_hp" id="ulasan-no-hp" autocomplete="tel" maxlength="30" placeholder="Contoh: 081234567890" required aria-describedby="ulasan-no-hp-error">
                            <div class="text-danger fs-7" id="ulasan-no-hp-error" data-ulasan-error="no_hp"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="ulasan-email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="ulasan-email" autocomplete="email" maxlength="254" required aria-describedby="ulasan-email-error">
                            <div class="text-danger fs-7" id="ulasan-email-error" data-ulasan-error="email"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="ulasan-isi">Saran atau kritik <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="isi" id="ulasan-isi" rows="4" minlength="10" maxlength="3000" required aria-describedby="ulasan-isi-error" placeholder="Tuliskan saran atau kritik Anda tentang pelayanan website ini."></textarea>
                            <div class="text-danger fs-7" id="ulasan-isi-error" data-ulasan-error="isi"></div>
                        </div>
                    </div>
                    <p class="text-muted fs-7 mt-4">Data kontak dan masukan Anda hanya dapat dilihat oleh administrator website.</p>
                    <div id="ulasan-message" class="mb-4" role="status" aria-live="polite" tabindex="-1"></div>
                    <button type="submit" class="btn btn-primary" id="ulasan-submit">Kirim ulasan</button>
                </form>
            </div>
        </div>
    </div>
</section>
