<!-- pilih template -->
<?= $this->extend('template/utils/layout') ?>

<!-- judul halaman -->
<?= $this->section('title') ?>
Login
<?= $this->endSection() ?>

<!-- isi halaman -->
<?= $this->section('content') ?>
<!--begin::Form-->
<form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="../../demo1/dist/index.html"
    action="#">
    <?= csrf_field(); ?>
    <!--begin::Heading-->
    <div class="text-center mb-11">
        <img alt="Logo"
            src="<?= $setting['logo_satker'] !== null ? base_url('logo/' . $setting['logo_satker']) : base_url('assets/media/logos/logoipsum-331.svg') ?>"
            class="logo-default h-40px h-lg-60px" />
        <!--begin::Title-->
        <h1 class="text-dark fw-bolder mb-3">Login Administrator</h1>
        <!--end::Title-->
    </div>
    <!--begin::Heading-->
    <!--begin::Input group=-->
    <div class="fv-row mb-3">
        <!--begin::Email-->
        <input type="text" placeholder="Username" name="email" autocomplete="off" class="form-control bg-transparent" />
        <!--end::Email-->
    </div>
    <!--end::Input group=-->
    <div class="fv-row mb-3">
        <!--begin::Password-->
        <div class="input-group">
            <input type="password" placeholder="Password" name="password" id="password" autocomplete="off"
                class="form-control bg-transparent" />
            <button type="button" class="btn btn-warning" id="togglePassword">
                <i class="fas fa-eye" id="passwordIcon"></i>
            </button>
        </div>
        <!--end::Password-->
    </div>
    <div class="input-group mb-3 w-100 border border-2 border-warning rounded rounded-2 p-1">
        <img id="captcha-img" class="rounded-start" src="<?= $captcha_url ?>" alt="CAPTCHA">
        <input type="text" name="captcha_input" placeholder="CAPTCHA" class="form-control" required>
        <button type="button" onclick="refreshCaptcha()" class="btn btn-warning">
            <span class="indicator-label"><i class="bi bi-arrow-clockwise"></i></span>
        </button>
    </div>
    <p></p>



    <!--begin::Submit button-->
    <div class="d-grid mb-10">
        <button type="submit" id="kt_sign_in_submit" class="btn btn-warning">
            <!--begin::Indicator label-->
            <span class="indicator-label">Masuk</span>
            <!--end::Indicator label-->
            <!--begin::Indicator progress-->
            <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            <!--end::Indicator progress-->
        </button>
    </div>
    <!--end::Submit button-->
    <!--begin::Sign up-->
    <!--end::Sign up-->
</form>
<!--end::Form-->
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const passwordIcon = document.getElementById('passwordIcon');

togglePassword.addEventListener('click', function() {
    // Toggle the input type between 'password' and 'text'
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;

    // Change the icon accordingly
    passwordIcon.classList.toggle('fa-eye');
    passwordIcon.classList.toggle('fa-eye-slash');
});

$(document).on('submit', '#kt_sign_in_form', function(e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "<?= site_url('api/user/login'); ?>",
        data: new FormData(this),
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(response) {
            window.location.href = "<?= site_url('admin'); ?>";
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            alert(xhr.responseText);
            // location.reload();
        }
    });
});

function refreshCaptcha() {
    document.getElementById('captcha-img').src = "<?= base_url('captcha') ?>?t=" + new Date().getTime();
}
</script>
<?= $this->endSection() ?>