# sqli-testbed (mysql) v0.5

## Usage

1) Clone repository:
```sh
cd /var/www/html ; git clone https://github.com/hvqzao/sqli-testbed sqli
```
2) Change password:
```sh
newpass=`apg -M NCL -m 12 -n 1` ; echo -e "New password: $newpass\nPress Enter to proceed, ^C to cancel." ; read ; sed -i "s/zoacUtOvee/$newpass/g" index.php
```
3) Setup MySQL (commands included in index.php)
4) Configure "Setup", "Drop" and "Filter" sections (uncomment appropriate entries in index.php)
5) Goal: Dump table with user passwords

Union based:
http://127.0.0.1/sqli/?u=1
    
Boolean based:
http://127.0.0.1/sqli/?b=1

## License

[MIT License](https://github.com/twbs/bootstrap/blob/master/LICENSE)

