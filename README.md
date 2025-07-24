# Kurumsal Servis Projesi

Merhaba, ben Melih Zorlu.  
Bu proje, kurum içindeki sistemlere entegre olacak şekilde REST ve SOAP protokolleriyle dış servislerden veri alabilen, bu verileri normalize edip cache'leyen, veritabanına kaydeden ve API üzerinden bu verileri sunabilen bir sistem olarak geliştirdim.


---

## Özellikler

Dış servislerle REST ve SOAP üzerinden veri alışverişi  
Gelen verilerin normalize edilmesi  
Redis ile 30 dakika süreli cache yapısı  
MySQL'e kalıcı veri kaydı  
Başarılı ve başarısız tüm işlemlerin loglanması  
JWT ile kullanıcı doğrulama ve yetkilendirme  
API üzerinden veri sunumu  
Laravel Sail ile Docker desteği  
Postman Collection (→ `docs/postman-collection.json` altında)

---

 Projeyi Çalıştırmak için

cd kurumsal-servis
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
