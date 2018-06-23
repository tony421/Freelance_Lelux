today=$(date +"%Y-%m-%d")
file="/home8/leluxtha/public_html/support-db-backup/lelux-backup-$today.sql"
mysqldump -u leluxtha_sup1 -pleluxsup1 leluxtha_support > "$file"