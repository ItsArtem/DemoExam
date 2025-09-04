1. Установка ПО
   1.1. На демоэкзамене необходимо уметь работать с веб-сервером (несколько программ, собраны в одном месте и позволяют делать сайт видимым по ip-адресу)
   XAMPP — это полностью бесплатный и простой в установке дистрибутив Apache, содержащий MariaDB, PHP и Perl. Пакет XAMPP с открытым исходным кодом разработан для невероятно простой установки и использования. https://www.apachefriends.org/
   chmod +x xampp-linux-x64-8.2.12-0-installer.run
   ./xampp-linux-x64-8.2.12-0-installer.run
   Команда chmod +x нужна для того, чтобы можно было запустить файл. Команда ./ запускает программу
2. БОМ 2.0 - https://bom.firpo.ru/Public/2645
dnf install libxcrypt-compat
dnf install libnsl
/opt/lampp/lampp start
