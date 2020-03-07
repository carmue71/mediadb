#Install MediaDB on a Debian based machine i.e. ubuntu, kbuntu or raspbian

#Variables
dir=`pwd`
libdir=/var/lib/mediadb
optdir=/opt/MediaDB
webdir=$optdir/public
installer=apt-get


#install mysql and apache
sudo apt-get install mysql-server apache2

#start mysql and apache
sudo service start mysql
sudo service start apache2

#make them start at boot time
sudo service enable mysql
sudo service enable apache2

#harden the mysql installation
sudo mysql_secure_installation

#install phpmyadmin 
#this installs most of the mysql and php packages and comes in handy for db maintainance
sudo $installer install phpmyadmin


#install git
sudo $installer install git

#retrieve the repository from git hub
git clone https://github.com/carmue71/mediadb.git

#create the mediadb
sudo mysql -u root -p < $dir/mediadb/install/createdeb.sql

sudo mkdir $libdir
sudo ln -s $dir/mediadb/assets $libdir/assets

sudo ln -s $optdir $dir/mediadb
sudo mkdir $webdir/MediaDBData

#install getid3
cd $dir/mediadb/dist/lib
wget https://github.com/JamesHeinrich/getID3/archive/master.zip
unzip master.zip
ln -s getID3-master/getid3/ .

cp $dir/mediadb/dist/src/mediadb/conf_default.php $dir/dist/src/mediadb/conf.php
cp $dir/mediadb/install/mediadb.conf /etc/apache2/conf-enabled/  
