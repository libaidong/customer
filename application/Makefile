# Config details

#allow for the username and password to be specified
ifdef DBUSER
	DB=sitesync
else 
	DBUSER=${shell whoami}
	DB=sitesync
	DBPASS=
endif

# MySQL config {{{
ifdef DBPASS
	MYSQL=mysql ${DB} -u"${DBUSER}" -p"${DBPASS}"
else
	MYSQL=mysql ${DB} -u"${DBUSER}"
endif
# }}}

permissions:
	-mkdir ../uploads
	-chmod -R 0777 ../uploads
	-mkdir ../data/reports
	-chmod -R 0777 ../data/reports

database: permissions database-clean
	@echo "Installing database schema..."
	${MYSQL} <../database/Schema.sql
	${MYSQL} <../database/Rialto.sql
	${MYSQL} <../database/Maven.sql
	wget -q -O /dev/null http://localhost/debug/processdevnotes
	wget -q -O /dev/null http://localhost/api/initcsvtest
	${MYSQL} <../database/basehours.sql
	# Update consumptiondata device to be first record in Meters list for testing purposes
	${MYSQL} -e "UPDATE consumptiondata SET deviceid = (SELECT deviceid from devices WHERE name = 'Level M1, Zone 3, North Tower AHAC') WHERE deviceid = (SELECT deviceid FROM devices WHERE ref = 'device3000');"

database-clean:
	@echo "Cleaning database [${DB}]..."
	${MYSQL} -e 'DROP DATABASE IF EXISTS ${DB}; CREATE DATABASE ${DB}'


# MC specific rules
maven-dcu:
	# Copy updated Maven docs into Git
	rsync -auvp '/home/mc/Papers/MFDC-ClientFiles/Maven Software Release/' '../docs/DCU'
