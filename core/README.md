# [pixelimity](https://github.com/pixelimity/pixelimity)


```

../ BASE / [CORE] / ...

```
Berpikiran untuk membuat direktori khusus sebagai tempat fle file system yang bersifat pribadi.
Sehingga Base document bisa lebih simple dan teratur, dan kita hanya perlu menambahkan defenisi, ex:

```php
 define('CORE', BASE.'core'.DS);
```

Tujuannya adalah:

  - Membuat file system lebih benar2 private
  - Menjadikannya Base lebih ramping
  - Karna harapan kita ingin mengembangkan, akan banyak libari dan class yang mungkin akan di tambahkan kedepan, ini akan
    membuatnya menjadi mudah. selain itu kita tidak kesulitan saat memindahkan file2 ini.
  - Sehingga pada akhirnya, kita akan membuat 1 controller khusus untuk memudahkan menangani file2 system tersebut.
  
Ini hanya sekedar pemikiran, ynag tentunya akan perlu dipertimbangkan dengan bijaksana.
