#!/usr/local/bin/node
var sh = require('execSync');

var fs = require('fs');

if(process.argv[2] && process.argv[3] && process.argv[4]) {
	var pwd = process.argv[2];
	var sql= process.argv[3];
	var db = process.argv[4];
}
else {
	console.log('Usage: checkdb <mysql root password> <sql schema file> <temporary DB> (optional: tempdb)'); 
	process.exit(1);
}

if(process.argv[5]) {
	var tempdb = process.argv[5];
}
else {
	var tempdb = 'db1';
}

//Create a temporary db
sh.exec('mysql -u root -p' + pwd + ' -e "drop database ' + tempdb +'"');
sh.exec('mysql -u root -p' + pwd + ' -e "create database ' + tempdb +'"');
sh.exec('mysql -u root -p' + pwd + ' ' + tempdb + ' < ' + sql);
var result = sh.exec('mysqldiff -u root -p ' + pwd + ' ' + tempdb + ' ' + db);

if(result.stdout){ //if it finds something, print it and write to a file and then quit returning 1
	result = result.stdout; 
	console.log(result);	
	fs.writeFileSync('./altersql.sql', result);

	process.exit(1);
}
else 
	console.log('Nothing found in database diff');
	process.exit(0);
/*
 

//using http://nodejs.org/api.html#_child_processes
var sys = require('sys')
var exec = require('child_process').exec;
var child = exec('mysqldiff -u root -p ' + pwd + ' ' + tempdb + ' ' + db, function(error, stdout, stderr) {

	if(stdout){ //if it finds something, print it and write to a file and then quit returning 1
		result = stdout.toString(); 
		console.log(result);	
		fs.writeFileSync('./altersql.sql', result);

		process.exit(1);
	}
	else 
		console.log('Nothing found in database diff');
		process.exit(0);
}); 
*/

