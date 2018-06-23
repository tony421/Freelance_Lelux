set today=%date%
set host=leluxthai.com.au
set user=ton
set pass=ton4153136
set zip_file=lelux.zip
set temp_file=ftp-lelux-deploy.txt
set local_root=C:\xampp\htdocs\lelux
set remote_root=/public_html/
set remote_dir=test

cd %local_root%
echo open %host%>%temp_file%
echo %user%>>%temp_file%
echo %pass%>>%temp_file%
echo cd %remote_root%>>%temp_file%
echo rename %remote_dir% %remote_dir%-%today%>>%temp_file%
echo mkdir %remote_dir%>>%temp_file%
echo cd %remote_dir%>>%temp_file%
echo put %zip_file%>>%temp_file%
echo disconnect>>%temp_file%
echo !>>%temp_file%

ftp -s:%temp_file%


