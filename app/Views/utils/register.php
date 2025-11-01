<!-- pilih template -->
<?= $this->extend('template/utils/layout') ?>

<!-- set title -->
<?= $this->section('title') ?>
    Registrasi
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!--begin::Form-->
<form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="../../demo1/dist/index.html" action="#">
    <!--begin::Heading-->
    <div class="text-center mb-11">
        <!--begin::Title-->
        <h1 class="text-dark fw-bolder mb-3">Sign Up</h1>
        <!--end::Title-->
        <!--begin::Subtitle-->
        <div class="text-gray-500 fw-semibold fs-6">Your Social Campaigns</div>
        <!--end::Subtitle=-->
    </div>
    <!--begin::Heading-->
    <!--begin::Login options-->
    <div class="row g-3 mb-9">
        <!--begin::Col-->
        <div class="col-md-12">
            <!--begin::Google link=-->
            <a href="<?=$url_oauth; ?>"
                class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                <img alt="Logo"
                    src="<?=base_url()?>assets/media/svg/brand-logos/google-icon.svg"
                    class="h-15px me-3" />Sign Up with Google</a>
            <!--end::Google link=-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Login options-->
    <!--begin::Separator-->
    <div class="separator separator-content my-14">
        <span class="w-125px text-gray-500 fw-semibold fs-7">Or with email</span>
    </div>
    <!--end::Separator-->
    <!--begin::Input group=-->
    <div class="fv-row mb-8">
        <!--begin::Email-->
        <input type="text" placeholder="Nama" name="nama" autocomplete="off"
            class="form-control bg-transparent" />
        <!--end::Email-->
    </div>
    <div class="fv-row mb-8">
        <!--begin::Email-->
        <input type="text" placeholder="Email" name="email" autocomplete="off"
            class="form-control bg-transparent" />
        <!--end::Email-->
    </div>
    <!--end::Input group=-->
    <div class="fv-row mb-8">
        <!--begin::Password-->
        <input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent" />
        <!--end::Password-->
    </div>

    <div class="fv-row mb-3">
        <!--begin::Password-->
        <input type="password" placeholder="Ulangi Password" name="password_confirmation" class="form-control bg-transparent" />
        <!--end::Password-->
    </div>
    <!--end::Input group=-->
    <!--begin::Submit button-->
    <div class="d-grid mb-10">
        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
            <!--begin::Indicator label-->
            <span class="indicator-label">Sign Up</span>
            <!--end::Indicator label-->
            <!--begin::Indicator progress-->
            <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            <!--end::Indicator progress-->
        </button>
    </div>
    <!--end::Submit button-->
</form>
<!--end::Form-->
<?= $this->endSection() ?>

<?= $this->section('script') ?>
    <script>
        $(document).on('submit','#kt_sign_in_form',function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?=site_url('api/user/register');?>",
                data: new FormData(this),
                dataType:'json',
                processData: false,
                contentType: false,
                success: function (response){                 
                    alert('Registrasi Berhasil, silahkan cek email anda untuk aktivasi akun');   
                    window.location.href = "<?=site_url('login');?>";
                },
                error: function(xhr){                
                    alert(xhr.responseJSON.message);
                }
            });
        });
    </script>
<?= $this->endSection() ?>