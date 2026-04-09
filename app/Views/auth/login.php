<?= $this->extend('CodeIgniter\Shield\Views\layout') ?>

<?= $this->section('title') ?><?= lang('Auth.login') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>
    <div class="container d-flex justify-content-center p-5">
        <div class="card col-12 col-md-5 shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-body p-5">
                <!-- Logo & Title -->
                <div class="text-center mb-5">
                    <div class="bg-indigo-600 text-white d-inline-flex align-items-center justify-content-center rounded-3 mb-3" style="width: 48px; height: 48px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    </div>
                    <h5 class="card-title mb-1 fw-bold font-heading" style="letter-spacing: -0.5px;">NexStock QR</h5>
                    <p class="text-muted small">Silakan masuk ke akun internal Anda</p>
                </div>

                <?php if (session('error') !== null) : ?>
                    <div class="alert alert-danger rounded-3 p-3 border-0 small mb-4" role="alert"><?= session('error') ?></div>
                <?php elseif (session('errors') !== null) : ?>
                    <div class="alert alert-danger rounded-3 p-3 border-0 small mb-4" role="alert">
                        <?php if (is_array(session('errors'))) : ?>
                            <?php foreach (session('errors') as $error) : ?>
                                <?= $error ?>
                                <br>
                            <?php endforeach ?>
                        <?php else : ?>
                            <?= session('errors') ?>
                        <?php endif ?>
                    </div>
                <?php endif ?>

                <?php if (session('message') !== null) : ?>
                <div class="alert alert-success rounded-3 p-3 border-0 small mb-4" role="alert"><?= session('message') ?></div>
                <?php endif ?>

                <form action="<?= url_to('login') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Username -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control border-light-subtle rounded-3" id="floatingInput" name="username" inputmode="text" autocomplete="username" placeholder="Username" value="<?= old('username') ?>" required>
                        <label for="floatingInput" class="text-muted small">Username</label>
                    </div>

                    <!-- Password -->
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control border-light-subtle rounded-3" id="floatingPassword" name="password" inputmode="text" autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required>
                        <label for="floatingPassword" class="text-muted small">Kata Sandi (Password)</label>
                    </div>

                    <!-- Remember me -->
                    <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                        <div class="form-check mb-4">
                            <input class="form-check-input border-light-subtle" type="checkbox" name="remember" id="rememberCheck" <?php if (old('remember')): ?> checked <?php endif ?>>
                            <label class="form-check-label text-muted small" for="rememberCheck">
                                Ingat saya di perangkat ini
                            </label>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-primary py-3 fw-bold rounded-3 border-0 shadow-sm" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
                            Masuk Sekarang
                        </button>
                    </div>
                </form>
                
                <div class="text-center">
                    <p class="text-muted small mb-0">Hanya untuk penggunaan internal Toko Sepatu.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS to enhance the layout -->
    <style>
        body { background-color: #f8fafc !important; }
        .card { box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05) !important; }
        .form-control:focus { 
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            border-color: #6366f1 !important;
        }
    </style>
<?= $this->endSection() ?>
