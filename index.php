<?php
include_once("koneksi.php");
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>To do list demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>

  	<div class="container p-5">
  		<h3>
    	To Do List
		    <small class="ms-3" style="font-size: 16px; color: #B5B5B5">
		        Catat semua hal yang akan kamu kerjakan disini.
		    </small>
		</h3>
		<hr>    
  	</div>
  	
  	<!--Form Input Data-->

  	<div class="container ps-5">
	  	<form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
		    <!-- Kode php untuk menghubungkan form dengan database -->
		    <?php
		    $isi = '';
		    $tgl_awal = '';
		    $tgl_akhir = '';
		    if (isset($_GET['id'])) {
		        $ambil = mysqli_query($koneksi, 
		        "SELECT * FROM kegiatanku 
		        WHERE id='" . $_GET['id'] . "'");
		        while ($row = mysqli_fetch_array($ambil)) {
		            $isi = $row['isi'];
		            $tgl_awal = $row['tgl_awal'];
		            $tgl_akhir = $row['tgl_akhir'];
		        }
		    ?>
		        <input type="hidden" name="id" value="<?php echo
		        $_GET['id'] ?>">
		    <?php
		    }
		    ?>
		    <div class="col">
		        <label for="inputIsi" class="form-label fw-bold">
		            Kegiatan
		        </label>
		        <input type="text" class="form-control" name="isi" id="inputIsi" placeholder="Kegiatan" value="<?php echo $isi ?>">
		    </div>
		    <div class="col">
		        <label for="inputTanggalAwal" class="form-label fw-bold">
		            Tanggal Awal
		        </label>
		        <input type="date" class="form-control" name="tgl_awal" id="inputTanggalAwal" placeholder="Tanggal Awal" value="<?php echo $tgl_awal ?>">
		    </div>
		    <div class="col mb-2">
		        <label for="inputTanggalAkhir" class="form-label fw-bold">
		        Tanggal Akhir
		        </label>
		        <input type="date" class="form-control" name="tgl_akhir" id="inputTanggalAkhir" placeholder="Tanggal Akhir" value="<?php echo $tgl_akhir ?>">
		    </div>
		    <div class="col">
		        <button type="submit" class="btn btn-primary rounded-pill px-3" name="simpan">Simpan</button>
		    </div>
		</form>	
  	</div>

  	<!-- Table-->
  	<div class="container p-5">
	  	<table class="table table-hover">
		    <!--thead atau baris judul-->
		    <thead>
		        <tr>
		            <th scope="col">#</th>
		            <th scope="col">Kegiatan</th>
		            <th scope="col">Awal</th>
		            <th scope="col">Akhir</th>
		            <th scope="col">Status</th>
		            <th scope="col">Aksi</th>
		        </tr>
		    </thead>
		    <!--tbody berisi isi tabel sesuai dengan judul atau head-->
		    <tbody>
		        <!-- Kode PHP untuk menampilkan semua isi dari tabel urut
		        berdasarkan status dan tanggal awal-->
		        <?php
		        $result = mysqli_query(
		            $koneksi,"SELECT * FROM kegiatanku ORDER BY status,tgl_awal"
		            );
		        $no = 1;
		        while ($data = mysqli_fetch_array($result)) {
		        ?>
		            <tr>
		                <th scope="row"><?php echo $no++ ?></th>
		                <td><?php echo $data['isi'] ?></td>
		                <td><?php echo $data['tgl_awal'] ?></td>
		                <td><?php echo $data['tgl_akhir'] ?></td>
		                <td>
		                    <?php
		                    if ($data['status'] == '1') {
		                    ?>
		                        <a class="btn btn-success rounded-pill px-3" type="button" 
		                        href="index.php?id=<?php echo $data['id'] ?>&aksi=ubah_status&status=0">
		                        Sudah
		                        </a>
		                    <?php
		                    } else {
		                    ?>
		                        <a class="btn btn-warning rounded-pill px-3" type="button" 
		                        href="index.php?id=<?php echo $data['id'] ?>&aksi=ubah_status&status=1">
		                        Belum</a>
		                    <?php
		                    }
		                    ?>
		                </td>
		                <td>
		                    <a class="btn btn-info rounded-pill px-3" 
		                    href="index.php?id=<?php echo $data['id'] ?>">Ubah
		                    </a>
		                    <a class="btn btn-danger rounded-pill px-3" 
		                    href="index.php?id=<?php echo $data['id'] ?>&aksi=hapus">Hapus
		                    </a>
		                </td>
		            </tr>
		        <?php
		        }
		        ?>
		    </tbody>
		</table>	
  	</div>

  	 <?php
	if (isset($_POST['simpan'])) {
	    if (isset($_POST['id'])) {
	        $ubah = mysqli_query($koneksi, "UPDATE kegiatanku SET 
	                                        isi = '" . $_POST['isi'] . "',
	                                        tgl_awal = '" . $_POST['tgl_awal'] . "',
	                                        tgl_akhir = '" . $_POST['tgl_akhir'] . "'
	                                        WHERE
	                                        id = '" . $_POST['id'] . "'");
	    } else {
	        $tambah = mysqli_query($koneksi, "INSERT INTO kegiatanku(isi,tgl_awal,tgl_akhir,status) 
	                                        VALUES ( 
	                                            '" . $_POST['isi'] . "',
	                                            '" . $_POST['tgl_awal'] . "',
	                                            '" . $_POST['tgl_akhir'] . "',
	                                            '0'
	                                            )");
	    }

	    echo "<script> 
	            document.location='index.php';
	            </script>";
	}

	if (isset($_GET['aksi'])) {
	    if ($_GET['aksi'] == 'hapus') {
	        $hapus = mysqli_query($koneksi, "DELETE FROM kegiatanku WHERE id = '" . $_GET['id'] . "'");
	    } else if ($_GET['aksi'] == 'ubah_status') {
	        $ubah_status = mysqli_query($koneksi, "UPDATE kegiatanku SET 
	                                        status = '" . $_GET['status'] . "' 
	                                        WHERE
	                                        id = '" . $_GET['id'] . "'");
	    }

	    echo "<script> 
	            document.location='index.php';
	            </script>";
	}
	?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>