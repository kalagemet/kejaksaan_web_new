<!-- pilih template -->
<?= $this->extend('template/utils/layout') ?>

<!-- set title -->
<?= $this->section('title') ?>
    Registrasi E-Datun
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <input type="hidden" name="id" value="<?=$id;?>">
    <input type="hidden" name="email" value="<?=$email;?>">
    <!--begin::Heading-->
    <div class="text-center mb-11">
        <!--begin::Title-->
        <span class="text-gray-600 fw-bolder fs-2qx mb-3">Selamat Datang <br>E-Datun</span>
        <!--end::Title-->
    </div>
    <!--begin::Heading-->
    <!--end::Separator-->
    <!--begin::Input group=-->
    <div
        class="notice bg-light-warning rounded border-warning border mt-7 flex-shrink-0 p-3 mb-2">
        <div class="fv-row mb-2">
            <!--begin::Email-->
            <select name="role" class="form-control">
                <option value="rakyat">Daftar sebagai rakyat</option>
                <option value="dewan">Daftar sebagai dewan</option>
            </select>
            <!--end::Email-->
        </div>
    </div>

    <!--end::Input group=-->
    <!--begin::Submit button-->
    <div class="d-grid mb-5">
        <button type="submit" id="kt_sign_in_submit"
            class="btn btn-warning border border-warning">
            <!--begin::Indicator label-->
            <span class="indicator-label fw-bold text-light">Selesai</span>
            <!--end::Indicator label-->
            <!--begin::Indicator progress-->
            <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            <!--end::Indicator progress-->
        </button>
    </div>
    <!--end::Submit button-->
    <div
        class="notice d-flex bg-light-secondary rounded border-secondary border border-dashed flex-shrink-0 p-6">
        <!--begin::Icon-->
        <!--begin::Svg Icon | path: icons/duotune/coding/cod004.svg-->
        <span class="svg-icon svg-icon-2tx svg-icon-secondary me-4">
            <i class="bi bi-info-square-fill fs-2"></i>
        </span>
        <!--end::Svg Icon-->
        <!--end::Icon-->
        <!--begin::Wrapper-->
        <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
            <!--begin::Content-->
            <div class="mb-3 mb-md-0 fw-semibold">
                <h4 class="text-gray-900 fs-6 fw-bold">INFO PENTING</h4>
                <div class="fs-8 text-gray-700">Lindungi akun Anda dengan tidak
                    memberikan ID pengguna dan kata sandi Anda pada siapapun. <br>
                    Segala risiko akibat penyalahgunaan ID pengguna menjadi tanggung
                    jawab pengguna sepenuhnya.</div>

            </div>
            <!--end::Content-->
        </div>
        <!--end::Wrapper-->
    </div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).on('submit','#kt_sign_in_form',function(e){
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "<?=site_url('api/user/register_oauth');?>",
            data: new FormData(this),
            dataType:'json',
            processData: false,
            contentType: false,
            success: function (response){                    
                window.location.href = "<?=site_url('profil');?>";
            },
            error: function(xhr){                
                alert(xhr.responseJSON.message);
            }
        });
    });
</script>
<?= $this->endSection() ?>