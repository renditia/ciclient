# ciclient
Client Server (Rest API)

1. Copy kan, file yang ada di libraries, ke folder libraries yang anda miliki
2. Tambahkan di auto load untuk 'rest' & 'curl'
    sbb :
    
    $autoload['libraries'] = array('curl', 'rest');
3. SSL (HTTPS) , Local Server / HTTP
   
   1. jika ingin mengakses API public secure (HTPPS) :
   Harus menambahkan sertificate untuk bisa menggakses hppt, cara nya (Window) :
   
       1. download cert terbaru di link berikut ini : https://curl.haxx.se/docs/caextract.html
       2. Copykan ke folder C:\xampp
       3. tambahkan baris berikut di php.ini (jangan lupa save):

           curl.cainfo="C:/xampp/cacert.pem"
           openssl.cafile="C:/xampp/cacert.pem"
       4. Restart xampp
  2. Jika hanya untuk akses local API / HTTP bisa langung tanpa harus setting certificate security
  
  
