# IERG4210

### Demo account:
- username: test@test.com
- pw: aaaaAAAA1111

### Some useful command:

`sudo tail -f /var/log/httpd/error_log`

`ALTER TABLE products MODIFY COLUMN pid INT auto_increment;
`

`sudo amazon-linux-extras install -y lamp-mariadb10.2-php7.2 php7.2
`

`yum install gd gd-devel php-gd
`
### Requirement
Random salt:
`sudo apt-get install mcrypt php7.1-mcrypt`

### Runserver
`sudo systemctl start httpd`
Ref. https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/CHAP_Tutorials.WebServerDB.CreateWebServer.html

### Change Owner
`sudo chown -R ec2-user /var/www/html`
