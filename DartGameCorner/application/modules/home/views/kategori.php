<div class="container mt-5">
    <div class="row-lg mt-5">
        <center>
            <h2>Kategori : <?= $judulh2['nama_kategori']; ?></h2>
        </center>
    </div>
    <div class="row mt-5">
        <?php foreach ($products as $row) : ?>
            <div class="col-md-3 mb-5">
                <div class="card-group">
                    <div class="card bg-light cardmovie h-100" style="border :none" data-aos="zoom-out-up" data-aos-offset="350" data-aos-duration="1000">
                        <a href="<?= base_url(); ?>home/detail/<?= $row['id_produk']; ?>" class="text-decoration-none">
                            <img src="<?= base_url('assets/images/produk/') . $row['gambar']; ?>" class="card-img-top image-resize rounded">
                            <div class="card-body">
                                <h5 class="card-title"><?= $row["nama_produk"]; ?></h5>
                            </div>
                        </a>
                        <div class="card-text text-muted ms-2 ket">
                            <h6><?= $row["keterangan"]; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</div>